<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransCallbackController extends Controller
{
    protected MidtransService $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Handle incoming HTTP Webhook notification from Midtrans.
     */
    public function handle(Request $request)
    {
        $payload = $request->all();

        Log::info('Midtrans Webhook Received', ['payload' => $payload]);

        // 1. Verifikasi Signature Hash Keamanan
        if (!$this->midtransService->verifySignature($payload)) {
            Log::warning('Midtrans Webhook Invalid Signature Key', [
                'order_id' => $payload['order_id'] ?? null,
                'signature' => $payload['signature_key'] ?? null,
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Invalid signature key',
            ], 403);
        }

        $rawOrderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;
        $paymentType = $payload['payment_type'] ?? null;
        $transactionId = $payload['transaction_id'] ?? null;
        $grossAmount = (float) ($payload['gross_amount'] ?? 0);

        // Cari order langsung atau buang suffix percobaan (-1234)
        $order = Order::where('order_number', $rawOrderId)->first();
        if (!$order && $rawOrderId) {
            $baseOrderId = preg_replace('/-[0-9]{3,6}$/', '', $rawOrderId);
            $order = Order::where('order_number', $baseOrderId)->first();
        }

        if (!$order) {
            Log::error("Order with number {$rawOrderId} not found for Midtrans notification");

            return response()->json([
                'status' => 'error',
                'message' => 'Order not found',
            ], 404);
        }


        // 2. Evaluasi Siklus Status Transaksi Midtrans
        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'accept') {
                $order->update([
                    'payment_status' => ($order->payment_scheme === 'dp_50') ? 'paid_dp' : 'paid_full',
                    'paid_amount' => $grossAmount,
                    'order_status' => 'verified',
                    'midtrans_transaction_id' => $transactionId,
                    'midtrans_payment_type' => $paymentType,
                    'midtrans_status' => $transactionStatus,
                    'midtrans_response' => $payload,
                ]);
            }
        } elseif ($transactionStatus === 'settlement') {
            $order->update([
                'payment_status' => ($order->payment_scheme === 'dp_50') ? 'paid_dp' : 'paid_full',
                'paid_amount' => $grossAmount,
                'order_status' => 'verified',
                'midtrans_transaction_id' => $transactionId,
                'midtrans_payment_type' => $paymentType,
                'midtrans_status' => $transactionStatus,
                'midtrans_response' => $payload,
            ]);
        } elseif ($transactionStatus === 'pending') {
            $order->update([
                'midtrans_transaction_id' => $transactionId,
                'midtrans_payment_type' => $paymentType,
                'midtrans_status' => $transactionStatus,
                'midtrans_response' => $payload,
            ]);
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $newOrderStatus = ($transactionStatus === 'expire') ? 'expired' : 'cancelled';
            $order->update([
                'order_status' => $newOrderStatus,
                'midtrans_status' => $transactionStatus,
                'midtrans_response' => $payload,
                'cancelled_at' => now(),
                'cancellation_reason' => "Status transaksi Midtrans: {$transactionStatus}",
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Notification handled successfully',
        ], 200);
    }
}
