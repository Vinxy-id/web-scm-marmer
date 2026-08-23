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
        $shipments = Shipment::with(['customer', 'creator', 'workOrder.product', 'workOrder.order'])
            ->orderBy('shipment_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10);

        $customers = Customer::orderBy('name', 'asc')->get();
        $readyProducts = Product::where('ready_stock', '>', 0)->get();
        $workOrders = WorkOrder::where('status', 'completed')->whereDoesntHave('shipment')->get();

        // SPK yang siap kirim (status completed) tapi belum diterbitkan surat jalan
        $pendingShipmentOrders = WorkOrder::with(['product', 'customer', 'order'])
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
        // DST-02 SOLVED: Strict packing verification requirement on server-side
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'work_order_id' => ['nullable', 'exists:work_orders,id'],
            'shipment_date' => ['required', 'date'],
            'expedition_name' => ['required', 'string', 'max:100'],
            'vehicle_plate' => ['nullable', 'string', 'max:30'],
            'vehicle_number' => ['nullable', 'string', 'max:30'],
            'driver_name' => ['nullable', 'string', 'max:100'],
            'tracking_number' => ['nullable', 'string', 'max:100'],
            'packing_verified' => ['nullable'],
            'wooden_packing_checked' => ['nullable'],
            'notes' => ['nullable', 'string'],
        ], [
            'expedition_name.required' => 'Nama ekspedisi atau armada pengiriman wajib diisi.',
        ]);

        $packingVerified = $request->boolean('packing_verified') || $request->boolean('wooden_packing_checked');
        if (!$packingVerified) {
            return redirect()->back()->withErrors([
                'packing_verified' => 'Verifikasi packing peti kayu solid wajib dicentang sebelum menerbitkan Surat Jalan.',
            ]);
        }

        $wo = null;
        if (!empty($validated['work_order_id'])) {
            $wo = WorkOrder::with('order')->findOrFail($validated['work_order_id']);

            // DST-04 SOLVED: Work order must be completed (passed QC)
            if ($wo->status !== 'completed') {
                return redirect()->back()->withErrors([
                    'work_order_id' => 'SPK ' . $wo->spk_number . ' belum selesai produksi (status: ' . $wo->status . '). Surat Jalan hanya dapat dibuat setelah lulus QC.',
                ]);
            }

            // DST-05 SOLVED: Duplicate shipment check for work order
            if (Shipment::where('work_order_id', $wo->id)->exists()) {
                return redirect()->back()->withErrors([
                    'work_order_id' => 'SPK ' . $wo->spk_number . ' sudah memiliki Surat Jalan yang diterbitkan sebelumnya.',
                ]);
            }
        }

        $shipmentCode = \App\Services\CodeGeneratorService::generateShipmentCode();

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $shipmentCode, $wo) {
            $shipment = Shipment::create([
                'shipment_code' => $shipmentCode,
                'work_order_id' => $validated['work_order_id'] ?? null,
                'customer_id' => $validated['customer_id'],
                'shipment_date' => $validated['shipment_date'],
                'expedition_name' => $validated['expedition_name'],
                'vehicle_plate' => $validated['vehicle_plate'] ?? ($validated['vehicle_number'] ?? null),
                'driver_name' => $validated['driver_name'] ?? null,
                'tracking_number' => $validated['tracking_number'] ?? null,
                'packing_verified' => true,
                'delivery_status' => 'packed',
                'notes' => $validated['notes'] ?? null,
                'created_by' => Auth::id() ?? 1, // DST-03 SOLVED: Standardized admin fallback
            ]);

            // DST-01 SOLVED: Sync status with public order if tied to e-commerce order
            if ($wo && $wo->order) {
                $wo->order->update(['order_status' => 'packing']);
            }
        });

        return redirect()->route('distribution.index')->with('success', 'Surat Jalan Pengiriman ' . $shipmentCode . ' berhasil diterbitkan.');
    }

    public function updateShipmentStatus(Request $request, Shipment $shipment)
    {
        if (!$request->has('delivery_status') && $request->has('status')) {
            $request->merge(['delivery_status' => $request->input('status')]);
        }

        // DST-06 SOLVED: Standardized clean delivery_status validation
        $validated = $request->validate([
            'delivery_status' => ['required', 'in:packed,in_transit,delivered,returned'],
            'tracking_number' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $shipment) {
            $updateData = [
                'delivery_status' => $validated['delivery_status'],
            ];

            if (!empty($validated['tracking_number'])) {
                $updateData['tracking_number'] = $validated['tracking_number'];
            }

            if (!empty($validated['notes'])) {
                $updateData['notes'] = $validated['notes'];
            }

            $shipment->update($updateData);

            // DST-01 SOLVED: Sync delivery status with public Order tracking
            if ($shipment->workOrder && $shipment->workOrder->order) {
                if ($validated['delivery_status'] === 'in_transit') {
                    $shipment->workOrder->order->update(['order_status' => 'shipped']);
                } elseif ($validated['delivery_status'] === 'delivered') {
                    $shipment->workOrder->order->update(['order_status' => 'delivered']);
                }
            }
        });

        return redirect()->route('distribution.index')->with('success', 'Status pengiriman ' . ($shipment->shipment_code ?? $shipment->id) . ' berhasil diperbarui ke ' . strtoupper($validated['delivery_status']) . '.');
    }
}
