<?php

namespace App\Http\Controllers;

use App\Models\WasteLog;
use App\Models\WorkOrder;
use Illuminate\Http\Request;

class WasteController extends Controller
{
    public function index()
    {
        // QC-11 SOLVED: Filter only relevant/active WorkOrders
        $workOrders = WorkOrder::with('product')
            ->whereIn('status', ['in_progress', 'qc_phase', 'completed'])
            ->orderBy('id', 'desc')
            ->take(30)
            ->get();

        $wasteLogs = WasteLog::with(['workOrder.product', 'step'])
            ->orderBy('logged_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15);

        $totalCladdingWaste = WasteLog::where('waste_type', 'sisa_layak_cladding')->sum('weight_kg');
        $totalSludgeWaste = WasteLog::where('waste_type', 'serbuk_bubut_sludge')->sum('weight_kg');

        return view('production.waste', compact(
            'workOrders',
            'wasteLogs',
            'totalCladdingWaste',
            'totalSludgeWaste'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'work_order_id' => ['required', 'exists:work_orders,id'],
            'waste_type' => ['required', 'in:sisa_layak_cladding,serbuk_bubut_sludge,bongkahan_urukan'],
            'weight_kg' => ['required', 'numeric', 'min:0'],
            'volume_m3' => ['nullable', 'numeric', 'min:0'],
            'reuse_status' => ['required', 'in:disimpan_daur_ulang,dijual_ke_pihak3,dibuang_ke_urukan'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['logged_at'] = now()->toDateString();

        // QC-08 SOLVED: Automatically link corresponding production step
        $wo = WorkOrder::find($validated['work_order_id']);
        if ($wo) {
            $step = null;
            if ($validated['waste_type'] === 'sisa_layak_cladding') {
                $step = $wo->steps()->where('step_name', 'pemotongan_slep')->first()
                     ?? $wo->steps()->where('step_name', 'pembelahan_bongkahan')->first();
            } elseif ($validated['waste_type'] === 'serbuk_bubut_sludge') {
                $step = $wo->steps()->where('step_name', 'pembubutan_bentuk')->first()
                     ?? $wo->steps()->where('step_name', 'penghalusan_poles')->first();
            }
            $validated['step_id'] = $step?->id;
        }

        WasteLog::create($validated);

        return redirect()->route('waste.index')->with('success', 'Pencatatan residu/limbah marmer berhasil disimpan.');
    }
}
