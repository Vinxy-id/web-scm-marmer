<?php

namespace App\Http\Controllers;

use App\Models\QcLog;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QcController extends Controller
{
    public function index()
    {
        $activeWorkOrders = WorkOrder::with('product')
            ->whereIn('status', ['in_progress', 'qc_phase'])
            ->get();

        $recentQcLogs = QcLog::with(['workOrder.product', 'inspector'])
            ->orderBy('inspection_date', 'desc')
            ->take(15)
            ->get();

        return view('qc.index', compact('activeWorkOrders', 'recentQcLogs'));
    }

    public function storeInspection(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'work_order_id' => ['required', 'exists:work_orders,id'],
            'stage' => ['required', 'in:qc1_raw_shape,qc2_final_polish'],
            'inspected_quantity' => ['required', 'integer', 'min:1'],
            'pass_quantity' => ['required', 'integer', 'min:0'],
            'rework_quantity' => ['required', 'integer', 'min:0'],
            'scrap_quantity' => ['required', 'integer', 'min:0'],
            'defect_type' => ['nullable', 'string', 'max:150'],
            'rework_action' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $validator->after(function ($validator) use ($request) {
            $inspected = (int) $request->input('inspected_quantity', 0);
            $pass = (int) $request->input('pass_quantity', 0);
            $rework = (int) $request->input('rework_quantity', 0);
            $scrap = (int) $request->input('scrap_quantity', 0);

            if (($pass + $rework + $scrap) !== $inspected) {
                $validator->errors()->add('inspected_quantity', 'Jumlah total unit (Lolos + Rework + Scrap) harus sama dengan Jumlah yang Diperiksa.');
            }
        });

        $validated = $validator->validate();

        DB::transaction(function () use ($validated) {
            $wo = WorkOrder::findOrFail($validated['work_order_id']);

            QcLog::create([
                'work_order_id' => $wo->id,
                'stage' => $validated['stage'],
                'inspector_id' => Auth::id() ?? 1,
                'inspected_quantity' => $validated['inspected_quantity'],
                'pass_quantity' => $validated['pass_quantity'],
                'rework_quantity' => $validated['rework_quantity'],
                'scrap_quantity' => $validated['scrap_quantity'],
                'defect_type' => $validated['defect_type'] ?? null,
                'rework_action' => $validated['rework_action'] ?? null,
                'inspection_date' => now()->toDateString(),
                'notes' => $validated['notes'] ?? null,
            ]);

            // If QC2 and Pass quantity > 0, we update work order and ready stock
            if ($validated['stage'] === 'qc2_final_polish') {
                $wo->update([
                    'completed_quantity' => $validated['pass_quantity'],
                    'scrap_quantity' => $validated['scrap_quantity'],
                    'status' => 'completed',
                    'completion_date' => now()->toDateString(),
                ]);

                // Increment ready stock of product
                $wo->product->increment('ready_stock', $validated['pass_quantity']);
            }
        });

        return redirect()->route('qc.index')->with('success', 'Hasil inspeksi QC berhasil disimpan ke basis data.');
    }
}
