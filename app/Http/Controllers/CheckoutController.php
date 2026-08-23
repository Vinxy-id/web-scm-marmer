<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);

        $artisanMap = [
            'marmer' => ['name' => 'UD Putra Abadi Marmer', 'phone' => '6281234567890', 'location' => 'Besole, Besuki, Tulungagung'],
            'onix' => ['name' => 'UD Cahaya Onix', 'phone' => '6285678901234', 'location' => 'Gamping, Campurdarat, Tulungagung'],
            'batu_kali' => ['name' => 'UD Putra Abadi Marmer', 'phone' => '6281234567890', 'location' => 'Besole, Besuki, Tulungagung'],
        ];

        $artisan = $artisanMap[$product->material_type] ?? ['name' => 'Sentra IKM Marmer & Onyx Tulungagung', 'phone' => '6281234567890', 'location' => 'Campurdarat, Tulungagung'];

        $banks = [
            'qris' => [
                'name' => 'QRIS Standar Pembayaran Nasional (NMID: ID102435890123)',
                'badge' => 'Instan Semua Bank & E-Wallet (BCA, BRI, Mandiri, GoPay, OVO, ShopeePay)',
                'type' => 'qris',
            ],
            'bank_bca' => [
                'name' => 'Bank BCA',
                'account_number' => '180-889-7721',
                'account_name' => 'UD CAHAYA ONIX / PUTRA ABADI',
                'type' => 'bank',
            ],
            'bank_bri' => [
                'name' => 'Bank BRI',
                'account_number' => '0129-01-004819-53-8',
                'account_name' => 'IKM MARMER TULUNGAGUNG',
                'type' => 'bank',
            ],
            'bank_mandiri' => [
                'name' => 'Bank Mandiri',
                'account_number' => '144-00-1928374-1',
                'account_name' => 'UD CAHAYA ONIX MARMER',
                'type' => 'bank',
            ],
        ];

        return view('public.checkout', compact('product', 'artisan', 'banks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:50'],
            'receiver_name' => ['required', 'string', 'max:150'],
            'receiver_phone' => ['required', 'string', 'max:25'],
            'shipping_city' => ['required', 'string', 'max:100'],
            'shipping_address' => ['required', 'string', 'max:500'],
            'payment_scheme' => ['required', 'in:dp_50,full_100'],
            'payment_method' => ['required', 'in:qris,bank_bca,bank_bri,bank_mandiri'],
            'custom_notes' => ['nullable', 'string', 'max:500'],
        ], [
            'receiver_name.required' => 'Nama lengkap penerima wajib diisi.',
            'receiver_phone.required' => 'Nomor WhatsApp / HP wajib diisi.',
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
                    'customer_code' => 'CUST-' . strtoupper(Str::random(6)),
                    'name' => $validated['receiver_name'],
                    'phone' => $cleanPhone,
                    'address' => $validated['shipping_address'],
                    'city' => $validated['shipping_city'],
                    'customer_type' => 'retail',
                ]
            );

            // 2. Generate unique order number
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(4));

            // 3. Create linked WorkOrder (SPK) in production queue
            $spkNumber = 'SPK-' . date('Y') . '-' . str_pad((WorkOrder::count() + 1), 3, '0', STR_PAD_LEFT);
            $workOrder = WorkOrder::create([
                'spk_number' => $spkNumber,
                'product_id' => $product->id,
                'customer_id' => $customer->id,
                'target_quantity' => $qty,
                'completed_quantity' => 0,
                'scrap_quantity' => 0,
                'status' => 'scheduled',
                'priority' => ($validated['payment_scheme'] === 'full_100') ? 'high' : 'normal',
                'start_date' => now()->toDateString(),
                'due_date' => now()->addDays(7)->toDateString(),
                'notes' => 'Pesanan E-Commerce Web: ' . $orderNumber . ' - Pembeli: ' . $validated['receiver_name'] . ' (' . $validated['shipping_city'] . ')',
                'created_by' => \App\Models\User::value('id') ?? 1,
            ]);

            // 4. Create Order
            $paidTarget = ($validated['payment_scheme'] === 'dp_50') ? ($totalAmount * 0.5) : $totalAmount;

            $newOrder = Order::create([
                'order_number' => $orderNumber,
                'customer_id' => $customer->id,
                'product_id' => $product->id,
                'work_order_id' => $workOrder->id,
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
            ]);

            return $newOrder;
        });

        return redirect()->route('checkout.invoice', $order->order_number)->with('success', 'Pesanan berhasil dibuat! Silakan selesaikan pembayaran untuk memulai pengerjaan.');
    }

    public function invoice($orderNumber)
    {
        $order = Order::with(['product.category', 'customer', 'workOrder'])->where('order_number', $orderNumber)->firstOrFail();

        $artisanMap = [
            'marmer' => ['name' => 'UD Putra Abadi Marmer', 'phone' => '6281234567890'],
            'onix' => ['name' => 'UD Cahaya Onix', 'phone' => '6285678901234'],
            'batu_kali' => ['name' => 'UD Putra Abadi Marmer', 'phone' => '6281234567890'],
        ];
        $artisan = $artisanMap[$order->product->material_type] ?? ['name' => 'Sentra IKM Marmer & Onyx Tulungagung', 'phone' => '6281234567890'];

        $banks = [
            'qris' => [
                'name' => 'QRIS Standar Pembayaran Nasional',
                'account_number' => 'NMID: ID102435890123',
                'account_name' => 'IKM MARMER & ONYX TULUNGAGUNG',
            ],
            'bank_bca' => [
                'name' => 'Bank BCA',
                'account_number' => '180-889-7721',
                'account_name' => 'UD CAHAYA ONIX / PUTRA ABADI',
            ],
            'bank_bri' => [
                'name' => 'Bank BRI',
                'account_number' => '0129-01-004819-53-8',
                'account_name' => 'IKM MARMER TULUNGAGUNG',
            ],
            'bank_mandiri' => [
                'name' => 'Bank Mandiri',
                'account_number' => '144-00-1928374-1',
                'account_name' => 'UD CAHAYA ONIX MARMER',
            ],
        ];

        $selectedBank = $banks[$order->payment_method] ?? $banks['qris'];

        $billAmount = ($order->payment_scheme === 'dp_50') 
            ? ($order->total_amount * 0.5) + $order->unique_code 
            : $order->total_amount + $order->unique_code;

        return view('public.invoice', compact('order', 'artisan', 'selectedBank', 'billAmount'));
    }

    public function tracking(Request $request)
    {
        $searchNumber = trim($request->input('order_number', ''));
        $order = null;

        if (!empty($searchNumber)) {
            $order = Order::with(['product', 'workOrder.shipment', 'customer'])
                ->where('order_number', $searchNumber)
                ->orWhereHas('workOrder', function ($q) use ($searchNumber) {
                    $q->where('spk_number', $searchNumber);
                })
                ->first();
        }

        return view('public.tracking', compact('order', 'searchNumber'));
    }
}
