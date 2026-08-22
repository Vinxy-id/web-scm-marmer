<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\Customer;
use App\Models\Product;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DistributionController extends Controller
{
    public function index(Request $request)
    {
        $shipments = Shipment::with(['customer', 'creator', 'workOrder.product'])
            ->orderBy('shipment_date', 'desc')
            ->paginate(10);

        $customers = Customer::all();
        $readyProducts = Product::where('ready_stock', '>', 0)->get();
        $workOrders = WorkOrder::whereIn('status', ['completed', 'qc_phase'])->get();

        // SPK yang siap kirim (status completed) tapi belum diterbitkan surat jalan
        $pendingShipmentOrders = WorkOrder::with(['product', 'customer'])
            ->where('status', 'completed')
            ->whereDoesntHave('shipment')
            ->orderBy('due_date', 'desc')
            ->get();

        $stats = [
            'pending_approval' => $pendingShipmentOrders->count(),
            'packed' => Shipment::where('delivery_status', 'packed')->count(),
            'in_transit' => Shipment::where('delivery_status', 'in_transit')->count(),
            'delivered' => Shipment::where('delivery_status', 'delivered')->count(),
        ];

        $prefillSpkId = $request->query('spk_id');

        return view('distribution.index', compact('shipments', 'customers', 'readyProducts', 'workOrders', 'pendingShipmentOrders', 'stats', 'prefillSpkId'));
    }

    public function storeShipment(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'work_order_id' => ['nullable', 'exists:work_orders,id'],
            'shipment_date' => ['required', 'date'],
            'expedition_name' => ['nullable', 'string', 'max:100'],
            'vehicle_number' => ['nullable', 'string', 'max:30'],
            'vehicle_plate' => ['nullable', 'string', 'max:30'],
            'driver_name' => ['nullable', 'string', 'max:100'],
            'wooden_packing_checked' => ['nullable', 'boolean'],
            'packing_verified' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string'],
        ]);

        $shipmentCode = 'SJ/' . date('Y/m/') . str_pad(Shipment::count() + 1, 4, '0', STR_PAD_LEFT);

        Shipment::create([
            'shipment_code' => $shipmentCode,
            'work_order_id' => $validated['work_order_id'] ?? null,
            'customer_id' => $validated['customer_id'],
            'shipment_date' => $validated['shipment_date'],
            'expedition_name' => $validated['expedition_name'] ?? 'Armada Sendiri',
            'vehicle_plate' => $validated['vehicle_plate'] ?? ($validated['vehicle_number'] ?? null),
            'driver_name' => $validated['driver_name'] ?? null,
            'packing_verified' => $validated['packing_verified'] ?? ($validated['wooden_packing_checked'] ?? false),
            'delivery_status' => 'packed',
            'notes' => $validated['notes'] ?? null,
            'created_by' => Auth::id() ?? 11,
        ]);

        return redirect()->route('distribution.index')->with('success', 'Surat Jalan Pengiriman ' . $shipmentCode . ' berhasil diterbitkan.');
    }

    public function updateShipmentStatus(Request $request, Shipment $shipment)
    {
        $validated = $request->validate([
            'status' => ['nullable', 'in:prepared,packed,in_transit,delivered,returned,cancelled'],
            'delivery_status' => ['nullable', 'in:packed,in_transit,delivered,returned'],
            'tracking_number' => ['nullable', 'string'],
        ]);

        $status = $validated['delivery_status'] ?? ($validated['status'] ?? 'packed');
        if ($status === 'prepared') $status = 'packed';
        if ($status === 'cancelled') $status = 'returned';

        $shipment->update([
            'delivery_status' => $status,
            'tracking_number' => $validated['tracking_number'] ?? $shipment->tracking_number,
        ]);

        return redirect()->route('distribution.index')->with('success', 'Status pengiriman ' . ($shipment->shipment_code ?? $shipment->id) . ' diperbarui.');
    }
}
