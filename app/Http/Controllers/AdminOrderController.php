<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\WorkOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminOrderController extends Controller
{
    /**
     * Display Admin Order Management Page.
     */
    public function index(Request $request)
    {
        $statusFilter = $request->input('status', 'all');
        $search = trim($request->input('search', ''));

        $query = Order::with(['product.category', 'customer', 'workOrder'])->latest();

        // 1. Filter by Status
        if ($statusFilter !== 'all') {
            if ($statusFilter === 'pending') {
                $query->where('order_status', 'pending_payment');
            } elseif ($statusFilter === 'in_production') {
                $query->whereIn('order_status', ['in_production', 'qc_phase', 'packing']);
            } elseif ($statusFilter === 'completed') {
                $query->whereIn('order_status', ['shipped', 'delivered']);
            } elseif ($statusFilter === 'cancelled') {
                $query->whereIn('order_status', ['cancelled', 'expired']);
            } else {
                $query->where('order_status', $statusFilter);
            }
        }

        // 2. Search Keyword
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('receiver_name', 'like', "%{$search}%")
                  ->orWhere('receiver_phone', 'like', "%{$search}%")
                  ->orWhere('shipping_city', 'like', "%{$search}%")
                  ->orWhereHas('product', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%")
                         ->orWhere('product_code', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->paginate(10)->withQueryString();

        // 3. Stats Summary Cards
        $stats = [
            'total_orders' => Order::count(),
            'pending_payment' => Order::where('order_status', 'pending_payment')->count(),
            'in_production' => Order::whereIn('order_status', ['in_production', 'qc_phase', 'packing'])->count(),
            'completed' => Order::whereIn('order_status', ['shipped', 'delivered'])->count(),
            'cancelled' => Order::whereIn('order_status', ['cancelled', 'expired'])->count(),
        ];

        return view('orders.index', compact('orders', 'stats', 'statusFilter', 'search'));
    }

    /**
     * Verify payment and generate official SPK Work Order (Gate 2 Validation).
     */
    public function verifyAndGenerateSpk(Request $request, Order $order)
    {
        if (!$order->canBeVerified()) {
            return redirect()->back()->with('error', 'Pesanan ini tidak dapat diverifikasi atau sudah memiliki SPK aktif.');
        }

        $creatorId = Auth::id() ?? User::value('id') ?? 1;

        DB::transaction(function () use ($order, $creatorId) {
            // 1. Determine paid amount & status
            $isDp = ($order->payment_scheme === 'dp_50');
            $paidTarget = $isDp ? ($order->total_amount * 0.5) : $order->total_amount;
            $paymentStatus = $isDp ? 'paid_dp' : 'paid_full';

            // 2. Generate SPK Number
            $spkNumber = 'SPK-' . date('Y') . '-' . str_pad((WorkOrder::count() + 1), 3, '0', STR_PAD_LEFT);

            // 3. Create WorkOrder in Workshop Kanban
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
                'created_by' => $creatorId,
            ]);

            // 4. Update Order record
            $order->update([
                'work_order_id' => $workOrder->id,
                'payment_status' => $paymentStatus,
                'paid_amount' => $paidTarget,
                'order_status' => 'in_production',
            ]);
        });

        return redirect()->route('orders.index')
                         ->with('success', "Pembayaran untuk pesanan #{$order->order_number} berhasil diverifikasi! SPK Produksi {$order->fresh()->workOrder->spk_number} telah resmi diterbitkan ke papan Kanban.");
    }

    /**
     * Cancel an order with a specified reason.
     */
    public function cancel(Request $request, Order $order)
    {
        $validated = $request->validate([
            'cancellation_reason' => ['required', 'string', 'max:255'],
        ], [
            'cancellation_reason.required' => 'Alasan pembatalan wajib diisi.',
        ]);

        DB::transaction(function () use ($order, $validated) {
            $order->update([
                'order_status' => 'cancelled',
                'cancellation_reason' => $validated['cancellation_reason'],
                'cancelled_at' => now(),
            ]);

            // If work order exists, mark it as cancelled
            if ($order->work_order_id && $order->workOrder) {
                $order->workOrder->update(['status' => 'cancelled']);
            }
        });

        return redirect()->route('orders.index')
                         ->with('success', "Pesanan #{$order->order_number} berhasil dibatalkan.");
    }

    /**
     * Permanently delete a spam order.
     */
    public function destroy(Order $order)
    {
        if ($order->work_order_id && $order->order_status !== 'cancelled') {
            return redirect()->back()->with('error', 'Pesanan yang sedang aktif di produksi tidak dapat dihapus langsung.');
        }

        $orderNumber = $order->order_number;
        $order->delete();

        return redirect()->route('orders.index')
                         ->with('success', "Pesanan spam #{$orderNumber} berhasil dihapus permanen dari sistem.");
    }
}
