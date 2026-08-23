<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\WorkOrder;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Show Public Checkout Page for a specific product.
     */
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);

        $isPutraAbadi = in_array($product->material_type ?? '', ['batu_kali']) || 
                       str_contains(strtolower($product->name ?? ''), 'kali') || 
                       str_contains(strtolower($product->name ?? ''), 'stepping') || 
                       str_contains(strtolower($product->name ?? ''), 'lampu');

        $artisan = $isPutraAbadi ? [
            'name' => 'UD Putra Abadi',
            'owner' => 'Efri Saputra',
            'phone' => '6281298765432',
            'bank_name' => 'Bank Mandiri',
            'account_number' => '144-00-1928374-1',
            'account_holder' => 'UD Putra Abadi - Efri Saputra',
        ] : [
            'name' => 'UD Cahaya Onix',
            'owner' => 'M. Ilham Nur Amali',
            'phone' => '6281234567890',
            'bank_name' => 'Bank BCA',
            'account_number' => '048-1928-384',
            'account_holder' => 'UD Cahaya Onix - M. Ilham',
        ];

        $banks = [
            'bank_bca' => [
                'name' => 'Bank BCA',
                'number' => '048-1928-384',
                'holder' => 'UD Cahaya Onix / Mitra IKM Tulungagung',
            ],
            'bank_bri' => [
                'name' => 'Bank BRI',
                'number' => '0123-01-098765-50-8',
                'holder' => 'Sentra IKM Marmer Onyx Tulungagung',
            ],
            'bank_mandiri' => [
                'name' => 'Bank Mandiri',
                'number' => '144-00-1928374-1',
                'holder' => 'UD Putra Abadi / Sentra Kerajinan Batu',
            ],
        ];

        return view('public.checkout', compact('product', 'artisan', 'banks'));
    }

    /**
     * Store incoming checkout order (Gate 1: Pending Payment).
     * NOTE: Does NOT create a WorkOrder in workshop until verified by Admin!
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:50'],
            'receiver_name' => ['required', 'string', 'max:150'],
            'receiver_phone' => ['required', 'string', 'regex:/^(\+62|62|0)8[1-9][0-9]{6,11}$/'],
            'shipping_city' => ['required', 'string', 'max:100'],
            'shipping_address' => ['required', 'string', 'max:500'],
            'payment_scheme' => ['required', 'in:dp_50,full_100'],
            'payment_method' => ['required', 'in:qris,bank_bca,bank_bri,bank_mandiri'],
            'custom_notes' => ['nullable', 'string', 'max:500'],
        ], [
            'receiver_name.required' => 'Nama lengkap penerima wajib diisi.',
            'receiver_phone.required' => 'Nomor WhatsApp / HP wajib diisi.',
            'receiver_phone.regex' => 'Format nomor WhatsApp tidak valid (Contoh: 081234567890).',
            'shipping_city.required' => 'Kota tujuan pengiriman wajib diisi.',
            'shipping_address.required' => 'Alamat lengkap pengiriman wajib diisi.',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $qty = (int) $validated['quantity'];
        $unitPrice = (float) $product->selling_price;
        $totalAmount = $unitPrice * $qty;
        $uniqueCode = rand(100, 999);

        $order = DB::transaction(function () use ($validated, $product, $qty, $unitPrice, $totalAmount, $uniqueCode) {
            // 1. Create or retrieve Customer record
            $cleanPhone = preg_replace('/[^0-9]/', '', $validated['receiver_phone']);
            $customer = Customer::firstOrCreate(
                ['phone' => $cleanPhone],
                [
                    'customer_code' => \App\Services\CodeGeneratorService::generateCustomerCode(),
                    'name' => $validated['receiver_name'],
                    'phone' => $cleanPhone,
                    'address' => $validated['shipping_address'],
                    'city' => $validated['shipping_city'],
                    'customer_type' => 'retail',
                ]
            );

            // 2. Generate unique non-sequential order number
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(4));

            // 3. Create Order without WorkOrder (Gate 1: Pending Payment, Expires in 24 hours)
            $newOrder = Order::create([
                'order_number' => $orderNumber,
                'customer_id' => $customer->id,
                'product_id' => $product->id,
                'work_order_id' => null, // Will be assigned by Admin when payment is verified
                'quantity' => $qty,
                'payment_scheme' => $validated['payment_scheme'],
                'payment_method' => $validated['payment_method'],
                'unit_price' => $unitPrice,
                'total_amount' => $totalAmount,
                'paid_amount' => 0,
                'unique_code' => $uniqueCode,
                'payment_status' => 'unpaid',
                'order_status' => 'pending_payment',
                'shipping_address' => $validated['shipping_address'],
                'shipping_city' => $validated['shipping_city'],
                'receiver_name' => $validated['receiver_name'],
                'receiver_phone' => $validated['receiver_phone'],
                'custom_notes' => $validated['custom_notes'] ?? null,
                'expires_at' => now()->addHours(24),
            ]);

            return $newOrder;
        });

        return redirect()->route('checkout.invoice', $order->order_number)
                         ->with('success', 'Pesanan berhasil dibuat! Silakan selesaikan pembayaran sebelum batas waktu berakhir.');
    }

    /**
     * Display Digital Invoice.
     */
    public function invoice($orderNumber)
    {
        $order = Order::with(['product.category', 'customer', 'workOrder'])
                      ->where('order_number', $orderNumber)
                      ->firstOrFail();

        // Check if order has expired
        if ($order->isExpired() && $order->order_status === 'pending_payment') {
            $order->update(['order_status' => 'expired']);
        }

        $artisanPhone = '6281234567890';
        if (str_contains(strtolower($order->product->name ?? ''), 'kali') || ($order->product->material_type ?? '') === 'batu_kali') {
            $artisanPhone = '6281298765432';
        }

        $targetTransferAmount = ($order->payment_scheme === 'dp_50') 
            ? (($order->total_amount * 0.5) + $order->unique_code) 
            : ($order->total_amount + $order->unique_code);

        $waConfirmMsg = "Halo Pengrajin E-SCM, saya telah melakukan pembayaran untuk Pesanan *" . $order->order_number . "* (" . $order->product->name . ") sebesar Rp " . number_format($targetTransferAmount, 0, ',', '.') . ". Mohon diverifikasi agar SPK produksi dapat diterbitkan. Terima kasih.";
        $waConfirmUrl = "https://wa.me/{$artisanPhone}?text=" . urlencode($waConfirmMsg);

        $banks = [
            'qris' => [
                'type' => 'QRIS',
                'name' => 'QRIS Standar Nasional (BCA, Mandiri, BRI, BNI, GoPay, OVO, ShopeePay)',
                'number' => 'NMID: ID1020304050607',
                'holder' => 'E-SCM MARMER ONYX TULUNGAGUNG',
            ],
            'bank_bca' => [
                'type' => 'Transfer Bank',
                'name' => 'Bank Central Asia (BCA)',
                'number' => '048-1928-384',
                'holder' => 'UD CAHAYA ONIX',
            ],
            'bank_bri' => [
                'type' => 'Transfer Bank',
                'name' => 'Bank Rakyat Indonesia (BRI)',
                'number' => '0123-01-098765-50-8',
                'holder' => 'SENTRA IKM MARMER TULUNGAGUNG',
            ],
            'bank_mandiri' => [
                'type' => 'Transfer Bank',
                'name' => 'Bank Mandiri',
                'number' => '144-00-1928374-1',
                'holder' => 'UD PUTRA ABADI',
            ],
        ];

        return view('public.invoice', compact('order', 'targetTransferAmount', 'waConfirmUrl', 'banks'));
    }

    /**
     * Public Order Tracking Page.
     */
    public function tracking(Request $request)
    {
        $searchNumber = trim($request->input('order_number', ''));
        $order = null;
        $workOrder = null;

        if (!empty($searchNumber)) {
            $order = Order::with(['product.category', 'customer', 'workOrder.shipment', 'workOrder.steps'])
                          ->where('order_number', $searchNumber)
                          ->orWhereHas('workOrder', function ($q) use ($searchNumber) {
                              $q->where('spk_number', $searchNumber);
                          })
                          ->first();

            if ($order && $order->isExpired() && $order->order_status === 'pending_payment') {
                $order->update(['order_status' => 'expired']);
            }

            if (!$order) {
                // Fallback: Check directly in standalone WorkOrder (SPK)
                $workOrder = \App\Models\WorkOrder::with(['product.category', 'customer', 'shipment', 'steps', 'order'])
                                                  ->where('spk_number', $searchNumber)
                                                  ->first();
            }
        }

        return view('public.tracking', compact('order', 'workOrder', 'searchNumber'));
    }
}
