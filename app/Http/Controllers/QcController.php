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
        $activeWorkOrders = WorkOrder::with(['product', 'steps', 'qcLogs'])
            ->whereIn('status', ['in_progress', 'qc_phase'])
            ->orderBy('id', 'desc')
            ->get();

        $recentQcLogs = QcLog::with(['workOrder.product', 'inspector'])
            ->orderBy('inspection_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15);

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

            $woId = $request->input('work_order_id');
            $stage = $request->input('stage');

            if ($woId && $stage === 'qc2_final_polish') {
                // QC-06 SOLVED: QC1 must be recorded before QC2
                $hasQc1 = QcLog::where('work_order_id', $woId)->where('stage', 'qc1_raw_shape')->exists();
                if (!$hasQc1) {
                    $validator->errors()->add('stage', 'SPK ini wajib menyelesaikan QC Tahap 1 (Bentuk Mentah) terlebih dahulu sebelum QC Tahap 2.');
                }

                // QC-02 SOLVED: QC2 cannot be recorded duplicate times
                $hasQc2 = QcLog::where('work_order_id', $woId)->where('stage', 'qc2_final_polish')->exists();
                if ($hasQc2) {
                    $validator->errors()->add('stage', 'QC Tahap 2 (Akhir & Poles) untuk SPK ini sudah pernah dicatat sebelumnya.');
                }
            }
        });

        $validated = $validator->validate();

        DB::transaction(function () use ($validated) {
            $wo = WorkOrder::findOrFail($validated['work_order_id']);

            // QC-08 SOLVED: Automatically link corresponding production step
            $step = null;
            if ($validated['stage'] === 'qc1_raw_shape') {
                $step = $wo->steps()->where('step_name', 'pembubutan_bentuk')->first() 
                     ?? $wo->steps()->where('step_name', 'pemotongan_slep')->first();
            } else {
                $step = $wo->steps()->where('step_name', 'inspeksi_qc')->first() 
                     ?? $wo->steps()->where('step_name', 'penghalusan_poles')->first();
            }

            QcLog::create([
                'work_order_id' => $wo->id,
                'step_id' => $step?->id,
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

            // QC-02 SOLVED: If QC2, update work order and ready stock with idempotency guard
            if ($validated['stage'] === 'qc2_final_polish') {
                $isNotCompletedYet = ($wo->status !== 'completed' && !$wo->completion_date);

                $wo->update([
                    'completed_quantity' => $validated['pass_quantity'],
                    'scrap_quantity' => (int) $wo->scrap_quantity + (int) $validated['scrap_quantity'],
                    'status' => 'completed',
                    'completion_date' => now()->toDateString(),
                ]);

                if ($isNotCompletedYet && $validated['pass_quantity'] > 0) {
                    $wo->product->increment('ready_stock', $validated['pass_quantity']);
                }

                $wo->steps()->update(['status' => 'completed']);
            } elseif ($validated['stage'] === 'qc1_raw_shape') {
                if ($validated['scrap_quantity'] > 0) {
                    $wo->increment('scrap_quantity', $validated['scrap_quantity']);
                }
            }
        });

        return redirect()->route('qc.index')->with('success', 'Hasil inspeksi QC berhasil disimpan ke basis data.');
    }
}
