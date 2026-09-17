<?php

namespace App\Services;

use App\Models\Order;
use App\Models\WorkOrder;
use App\Services\CodeGeneratorService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    /**
     * Inisialisasi konfigurasi Midtrans SDK.
     */
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = config('midtrans.is_sanitized', true);
        Config::$is3ds = config('midtrans.is_3ds', true);
    }

    /**
     * Generate Snap Token untuk transaksi pesanan E-Commerce.
     * Mendukung skema DP 50% dan Lunas 100%.
     *
     * @param Order $order
     * @return string|null
     */
    public function createSnapToken(Order $order, bool $freshAttempt = false): ?string
    {
        try {
            $isDp = ($order->payment_scheme === 'dp_50');
            $grossAmount = $isDp ? (int) round($order->total_amount * 0.5) : (int) round($order->total_amount);

            // Jika freshAttempt aktif (ganti metode bayar), berikan suffix unik agar Midtrans membuka opsi metode pembayaran baru
            $orderId = $freshAttempt ? ($order->order_number . '-' . substr(time(), -4)) : $order->order_number;

            // Item details
            $itemTitle = ($isDp ? '[DP 50%] ' : '') . ($order->product->name ?? 'Kerajinan Marmer Tulungagung');
            // Batasi panjang nama item agar sesuai limit spesifikasi Midtrans (max 50 karakter)
            $itemTitle = mb_substr($itemTitle, 0, 50);

            $itemPrice = (int) round($grossAmount / max(1, (int) $order->quantity));
            // Selisih pembulatan jika ada
            $adjustedTotal = $itemPrice * $order->quantity;
            $diff = $grossAmount - $adjustedTotal;

            $items = [
                [
                    'id' => $order->product->product_code ?? 'PRD-' . $order->product_id,
                    'price' => $itemPrice,
                    'quantity' => (int) $order->quantity,
                    'name' => $itemTitle,
                ],
            ];

            if ($diff !== 0) {
                $items[] = [
                    'id' => 'ADJ-DIFF',
                    'price' => $diff,
                    'quantity' => 1,
                    'name' => 'Penyesuaian Skema Nominal',
                ];
            }

            // Customer details
            $customerDetails = [
                'first_name' => $order->receiver_name,
                'phone' => $order->receiver_phone,
                'shipping_address' => [
                    'first_name' => $order->receiver_name,
                    'phone' => $order->receiver_phone,
                    'address' => $order->shipping_address,
                    'city' => $order->shipping_city,
                    'country_code' => 'IDN',
                ],
            ];

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $grossAmount,
                ],
                'item_details' => $items,
                'customer_details' => $customerDetails,
                'callbacks' => [
                    'finish' => route('checkout.invoice', $order->order_number),
                ],
            ];


            $snapToken = Snap::getSnapToken($params);

            // Simpan snap token ke record order
            $order->update([
                'snap_token' => $snapToken,
            ]);

            return $snapToken;
        } catch (\Throwable $e) {
            Log::error('Midtrans Snap Token Generation Failed: ' . $e->getMessage(), [
                'order_number' => $order->order_number,
                'trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }

    /**
     * Memverifikasi keaslian signature payload webhook Midtrans.
     *
     * @param array $payload
     * @return bool
     */
    public function verifySignature(array $payload): bool
    {
        $signatureKey = $payload['signature_key'] ?? '';
        $orderId = $payload['order_id'] ?? '';
        $statusCode = $payload['status_code'] ?? '';
        $grossAmount = $payload['gross_amount'] ?? '';
        $serverKey = config('midtrans.server_key');

        if (empty($signatureKey) || empty($orderId) || empty($statusCode) || empty($grossAmount) || empty($serverKey)) {
            return false;
        }

        $computedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        return hash_equals($signatureKey, $computedSignature);
    }

    /**
     * Cek status transaksi langsung ke API Midtrans dan sinkronkan dengan database lokal.
     * Sangat berguna untuk pengujian di localhost di mana webhook tidak bisa menjangkau IP lokal,
     * serta sebagai fallback otomatis jika webhook dari cloud terlambat.
     *
     * @param Order $order
     * @return string|null
     */
    public function syncOrderStatus(Order $order): ?string
    {
        if (empty(config('midtrans.server_key')) || empty($order->order_number)) {
            return null;
        }

        try {
            $response = \Midtrans\Transaction::status($order->order_number);
            $payload = (array) $response;

            $transactionStatus = $payload['transaction_status'] ?? null;
            $fraudStatus = $payload['fraud_status'] ?? null;
            $paymentType = $payload['payment_type'] ?? 'midtrans';
            $transactionId = $payload['transaction_id'] ?? null;
            $grossAmount = (float) ($payload['gross_amount'] ?? $order->total_amount);

            if ($transactionStatus === 'settlement' || ($transactionStatus === 'capture' && $fraudStatus === 'accept')) {
                $isDp = ($order->payment_scheme === 'dp_50');
                $paymentStatus = $isDp ? 'paid_dp' : 'paid_full';

                DB::transaction(function () use ($order, $paymentStatus, $grossAmount, $transactionId, $paymentType, $transactionStatus, $payload) {
                    if (!$order->work_order_id) {
                        $spkNumber = CodeGeneratorService::generateSpkNumber();
                        $workOrder = WorkOrder::create([
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

                    $order->update([
                        'payment_status' => $paymentStatus,
                        'paid_amount' => $grossAmount,
                        'order_status' => 'in_production',
                        'midtrans_transaction_id' => $transactionId,
                        'midtrans_payment_type' => $paymentType,
                        'midtrans_status' => $transactionStatus,
                        'midtrans_response' => $payload,
                        'work_order_id' => $order->work_order_id,
                    ]);
                });

                return $transactionStatus;
            } elseif ($transactionStatus === 'pending') {
                $order->update([
                    'midtrans_transaction_id' => $transactionId,
                    'midtrans_payment_type' => $paymentType,
                    'midtrans_status' => $transactionStatus,
                    'midtrans_response' => $payload,
                ]);

                return $transactionStatus;
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $newOrderStatus = ($transactionStatus === 'expire') ? 'expired' : 'cancelled';
                $order->update([
                    'order_status' => $newOrderStatus,
                    'midtrans_status' => $transactionStatus,
                    'midtrans_response' => $payload,
                    'cancelled_at' => now(),
                    'cancellation_reason' => "Status transaksi Midtrans: {$transactionStatus}",
                ]);

                return $transactionStatus;
            }
        } catch (\Throwable $e) {
            // Midtrans melempar exception 404 jika belum pernah dibuat transaksi oleh pembeli
            return null;
        }

        return null;
    }
}
