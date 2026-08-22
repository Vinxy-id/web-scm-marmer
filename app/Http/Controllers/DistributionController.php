<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DistributionController extends Controller
{
    public function index()
    {
        $shipments = Shipment::with(['customer', 'creator'])
            ->orderBy('shipment_date', 'desc')
            ->paginate(10);

        $customers = Customer::all();
        $readyProducts = Product::where('ready_stock', '>', 0)->get();

        return view('distribution.index', compact('shipments', 'customers', 'readyProducts'));
    }

    public function storeShipment(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'shipment_date' => ['required', 'date'],
            'expedition_name' => ['nullable', 'string', 'max:100'],
            'vehicle_number' => ['nullable', 'string', 'max:30'],
            'driver_name' => ['nullable', 'string', 'max:100'],
            'wooden_packing_checked' => ['required', 'boolean'],
            'notes' => ['nullable', 'string'],
        ]);

        $shipmentNumber = 'SJ/' . date('Y/m/') . str_pad(Shipment::count() + 1, 4, '0', STR_PAD_LEFT);

        Shipment::create([
            'shipment_number' => $shipmentNumber,
            'customer_id' => $validated['customer_id'],
            'shipment_date' => $validated['shipment_date'],
            'expedition_name' => $validated['expedition_name'] ?? null,
            'vehicle_number' => $validated['vehicle_number'] ?? null,
            'driver_name' => $validated['driver_name'] ?? null,
            'wooden_packing_checked' => $validated['wooden_packing_checked'],
            'status' => 'prepared',
            'notes' => $validated['notes'] ?? null,
            'created_by' => Auth::id() ?? 1,
        ]);

        return redirect()->route('distribution.index')->with('success', 'Surat Jalan Pengiriman ' . $shipmentNumber . ' berhasil diterbitkan.');
    }

    public function updateShipmentStatus(Request $request, Shipment $shipment)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:prepared,in_transit,delivered,cancelled'],
            'tracking_number' => ['nullable', 'string'],
        ]);

        $shipment->update($validated);

        return redirect()->route('distribution.index')->with('success', 'Status pengiriman ' . $shipment->shipment_number . ' diperbarui.');
    }
}
