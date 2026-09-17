<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\WorkOrder;
use App\Services\CodeGeneratorService;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
