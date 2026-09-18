<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\WorkOrder;
use App\Models\Customer;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    protected MidtransService $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Show Public Checkout Page for a specific product.
     */
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);

        // Determine Artisan Partner Info from Model Accessor
        $artisan = $product->artisan;

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
            'payment_method' => ['nullable', 'string', 'in:midtrans,qris,bank_bca,bank_bri,bank_mandiri'],
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
        $paymentMethod = $validated['payment_method'] ?? 'midtrans';
        $uniqueCode = ($paymentMethod === 'midtrans') ? 0 : rand(100, 999);

        $order = DB::transaction(function () use ($validated, $product, $qty, $unitPrice, $totalAmount, $paymentMethod, $uniqueCode) {

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
                'payment_method' => $paymentMethod,
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

        // 4. Generate Midtrans Snap Token if payment method is midtrans
        if ($order->payment_method === 'midtrans') {
            $this->midtransService->createSnapToken($order);
        }

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

        // Auto-sync status with Midtrans API for unpaid midtrans order (essential for localhost testing & fast fallback)
        if ($order->payment_method === 'midtrans' && !in_array($order->payment_status, ['paid_dp', 'paid_full']) && !$order->isExpired() && !$order->isCancelled()) {
            $syncedStatus = $this->midtransService->syncOrderStatus($order);
            if ($syncedStatus) {
                $order->refresh();
                if (in_array($order->payment_status, ['paid_dp', 'paid_full']) && !session()->has('success')) {
                    session()->flash('success', 'Pembayaran Midtrans berhasil terverifikasi otomatis! SPK pengerjaan bengkel telah resmi aktif.');
                }
            }
        }

        // Generate Snap Token if missing for midtrans unpaid order
        if ($order->payment_method === 'midtrans' && empty($order->snap_token) && $order->payment_status === 'unpaid' && !$order->isExpired()) {
            $this->midtransService->createSnapToken($order);
            $order->refresh();
        }

        $artisanPhone = '6281340231737';
        if (str_contains(strtolower($order->product->name ?? ''), 'kali') || ($order->product->material_type ?? '') === 'batu_kali') {
            $artisanPhone = '6281335022012';
        }

        $targetTransferAmount = ($order->payment_scheme === 'dp_50') 
            ? (($order->total_amount * 0.5) + ($order->payment_method === 'midtrans' ? 0 : $order->unique_code)) 
            : ($order->total_amount + ($order->payment_method === 'midtrans' ? 0 : $order->unique_code));

        $isPaid = in_array($order->payment_status, ['paid_dp', 'paid_full']) || in_array($order->order_status, ['verified', 'in_production', 'qc_phase', 'packing', 'shipped', 'delivered']);

        if ($isPaid) {
            $spkNumber = $order->workOrder->spk_number ?? 'SPK Terbit';
            $waConfirmMsg = "Halo Pengrajin E-SCM, saya telah menyelesaikan pembayaran untuk Pesanan *" . $order->order_number . "* (" . ($order->product->name ?? 'Kerajinan Marmer') . " - SPK: " . $spkNumber . "). Mohon informasi jadwal dan progres pengerjaannya. Terima kasih.";
        } else {
            $waConfirmMsg = "Halo Pengrajin E-SCM, saya telah melakukan pembayaran untuk Pesanan *" . $order->order_number . "* (" . ($order->product->name ?? 'Kerajinan Marmer') . ") sebesar Rp " . number_format($targetTransferAmount, 0, ',', '.') . ". Mohon diverifikasi agar SPK produksi dapat diterbitkan. Terima kasih.";
        }
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

        $snapUrl = config('midtrans.snap_url');
        $clientKey = config('midtrans.client_key');

        return view('public.invoice', compact('order', 'targetTransferAmount', 'waConfirmUrl', 'banks', 'snapUrl', 'clientKey'));
    }


    /**
     * Public Order Tracking Page (Supports Order Number, SPK Number, and Phone/WhatsApp Number).
     */
    public function tracking(Request $request)
    {
        $searchNumber = trim($request->input('order_number', ''));
        $order = null;
        $workOrder = null;
        $phoneOrders = collect();

        if (!empty($searchNumber)) {
            // 1. Cek apakah kata kunci adalah nomor HP / WhatsApp (>= 8 digit angka murni atau berawalan 08 / +62 / 62)
            $cleanPhone = preg_replace('/[^0-9]/', '', $searchNumber);
            $looksLikePhone = (strlen($cleanPhone) >= 8 && !str_starts_with(strtoupper($searchNumber), 'ORD') && !str_starts_with(strtoupper($searchNumber), 'SPK'));

            if ($looksLikePhone) {
                $altPhone1 = preg_replace('/^08/', '628', $cleanPhone);
                $altPhone2 = preg_replace('/^628/', '08', $cleanPhone);

                $phoneOrders = Order::with(['product.category', 'customer', 'workOrder'])
                                    ->where(function ($q) use ($cleanPhone, $altPhone1, $altPhone2) {
                                        $q->where('receiver_phone', 'like', "%{$cleanPhone}%")
                                          ->orWhere('receiver_phone', 'like', "%{$altPhone1}%")
                                          ->orWhere('receiver_phone', 'like', "%{$altPhone2}%");
                                    })
                                    ->latest()
                                    ->get();

                if ($phoneOrders->count() === 1) {
                    $order = Order::with(['product.category', 'customer', 'workOrder.shipment', 'workOrder.steps'])
                                  ->find($phoneOrders->first()->id);
                }
            }

            // 2. Jika bukan pencarian nomor telepon atau jika nomor telepon tidak ditemukan / cocok dengan 1 order saja
            if (!$looksLikePhone || ($looksLikePhone && $phoneOrders->isEmpty())) {
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
        }

        return view('public.tracking', compact('order', 'workOrder', 'searchNumber', 'phoneOrders'));
    }

    /**
     * Regenerate Midtrans Snap Token if customer wants to switch payment channel.
     */
    public function regenerateSnapToken($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        if (in_array($order->payment_status, ['paid_dp', 'paid_full']) || in_array($order->order_status, ['verified', 'in_production', 'qc_phase', 'packing', 'shipped', 'delivered'])) {
            return redirect()->route('checkout.invoice', $order->order_number)
                             ->with('info', 'Tagihan pesanan ini sudah berhasil dibayarkan, metode pembayaran tidak dapat diubah.');
        }

        if ($order->isExpired() || $order->isCancelled()) {
            return redirect()->route('checkout.invoice', $order->order_number)
                             ->with('info', 'Pesanan ini telah kadaluarsa atau dibatalkan.');
        }

        if ($order->payment_method === 'midtrans') {
            $this->midtransService->createSnapToken($order, true);
        }

        return redirect()->route('checkout.invoice', ['orderNumber' => $order->order_number, 'pay' => 1])
                         ->with('info', 'Sesi pembayaran diperbarui. Silakan pilih metode pembayaran baru yang Anda inginkan.');
    }

    public function checkPaymentStatus(Request $request, $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        // 1. Jika pesanan sudah tercatat lunas/DP di database
        if (in_array($order->payment_status, ['paid_dp', 'paid_full']) || in_array($order->order_status, ['verified', 'in_production', 'qc_phase', 'packing', 'shipped', 'delivered'])) {
            return redirect()->route('checkout.invoice', $order->order_number)
                             ->with('success', 'Pembayaran telah berhasil diverifikasi! SPK pengerjaan bengkel telah aktif.');
        }

        if ($order->payment_method === 'midtrans') {
            $frontStatus = $request->input('transaction_status');
            $frontTrxId = $request->input('transaction_id');
            $frontType = $request->input('payment_type');

            if ($frontStatus === 'settlement' || ($frontStatus === 'capture' && $request->input('fraud_status') !== 'challenge')) {
                $isDp = ($order->payment_scheme === 'dp_50');
                $paymentStatus = $isDp ? 'paid_dp' : 'paid_full';
                $grossAmount = ($order->payment_scheme === 'dp_50') ? ($order->total_amount * 0.5) : $order->total_amount;

                \Illuminate\Support\Facades\DB::transaction(function () use ($order, $paymentStatus, $grossAmount, $frontTrxId, $frontType, $frontStatus, $request) {
                    if (!$order->work_order_id) {
                        $spkNumber = \App\Services\CodeGeneratorService::generateSpkNumber();
                        $workOrder = \App\Models\WorkOrder::create([
                            'spk_number' => $spkNumber,
                            'product_id' => $order->product_id,
                            'customer_id' => $order->customer_id,
                            'target_quantity' => $order->quantity,
                            'completed_quantity' => 0,
                            'scrap_quantity' => 0,
                            'status' => 'scheduled',
                            'priority' => ($order->payment_scheme === 'full_100') ? 'high' : 'normal',
                            'start_date' => now()->toDateString(),
                            'due_date' => now()->addDays(7)->toDateString(),
                            'notes' => 'Pesanan E-Commerce: ' . $order->order_number . ' - Pembeli: ' . $order->receiver_name . ' (' . $order->shipping_city . ')',
                            'created_by' => 1,
                        ]);
                        $order->work_order_id = $workOrder->id;
                    }

                    $merged = is_array($order->midtrans_response) ? $order->midtrans_response : [];
                    $merged = array_merge($merged, $request->all());

                    $order->update([
                        'payment_status' => $paymentStatus,
                        'paid_amount' => $grossAmount,
                        'order_status' => 'in_production',
                        'midtrans_transaction_id' => $frontTrxId ?: $order->midtrans_transaction_id,
                        'midtrans_payment_type' => $frontType ?: $order->midtrans_payment_type,
                        'midtrans_status' => $frontStatus,
                        'midtrans_response' => $merged,
                        'work_order_id' => $order->work_order_id,
                    ]);
                });

                return redirect()->route('checkout.invoice', $order->order_number)
                                 ->with('success', 'Pembayaran Midtrans berhasil terkonfirmasi! SPK pengerjaan bengkel telah otomatis diterbitkan.');
            } elseif ($frontStatus === 'pending') {
                $merged = is_array($order->midtrans_response) ? $order->midtrans_response : [];
                $merged = array_merge($merged, $request->all());

                $order->update([
                    'midtrans_transaction_id' => $frontTrxId ?: $order->midtrans_transaction_id,
                    'midtrans_payment_type' => $frontType ?: $order->midtrans_payment_type,
                    'midtrans_status' => 'pending',
                    'midtrans_response' => $merged,
                ]);
            }

            $status = $this->midtransService->syncOrderStatus($order);
            $order->refresh();

            if (in_array($order->payment_status, ['paid_dp', 'paid_full'])) {
                return redirect()->route('checkout.invoice', $order->order_number)
                                 ->with('success', 'Pembayaran Midtrans berhasil terkonfirmasi! SPK pengerjaan bengkel telah otomatis diterbitkan.');
            }

            if ($status === 'pending' || $order->midtrans_status === 'pending') {
                return redirect()->route('checkout.invoice', $order->order_number)
                                 ->with('info', 'Transaksi terdeteksi dalam proses di Midtrans (' . $order->formatted_payment_type . '). Jika Anda sudah mentransfer, mohon tunggu 1-2 menit untuk validasi sistem.');
            }

            if ($order->isCancelled() || in_array($status, ['deny', 'expire', 'cancel'])) {
                return redirect()->route('checkout.invoice', $order->order_number)
                                 ->with('info', 'Status transaksi pembayaran Midtrans dibatalkan atau telah kadaluarsa.');
            }

            return redirect()->route('checkout.invoice', $order->order_number)
                             ->with('info', 'Sistem belum mendeteksi konfirmasi pembayaran baru dari saluran pembayaran. Jika Anda baru saja menyelesaikan transfer, mohon tunggu 1-2 menit lalu coba klik Cek Status kembali.');
        }

        return redirect()->route('checkout.invoice', $order->order_number);
    }
}
