<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\Product;
use App\Models\Customer;
use App\Models\ProductionStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductionController extends Controller
{
    public function index()
    {
        return redirect()->route('production.kanban');
    }

    public function kanban()
    {
        $products = Product::all();
        $customers = Customer::all();

        // 5 Kanban Columns
        $colAntrian = WorkOrder::with(['product', 'customer'])
            ->whereIn('status', ['draft', 'scheduled'])
            ->orderBy('priority', 'desc')
            ->get();

        // Col 2: In Slep (in_progress AND slep is running or pending, and bubut is not running)
        $colSlep = WorkOrder::with(['product', 'customer', 'steps'])
            ->where('status', 'in_progress')
            ->where(function ($query) {
                $query->whereHas('steps', function ($q) {
                    $q->where('step_name', 'pemotongan_slep')
                      ->whereIn('status', ['running', 'pending']);
                })
                ->whereDoesntHave('steps', function ($q) {
                    $q->where('step_name', 'pembubutan_bentuk')
                      ->where('status', 'running');
                })
                ->orWhereDoesntHave('steps'); // New in_progress defaults to initial Slep step
            })
            ->get();

        // Col 3: In Bubut (PRD-01 SOLVED: Only when bubut step is running)
        $colBubut = WorkOrder::with(['product', 'customer', 'steps'])
            ->where('status', 'in_progress')
            ->whereHas('steps', function ($q) {
                $q->where('step_name', 'pembubutan_bentuk')
                  ->where('status', 'running');
            })
            ->get();

        $colQc = WorkOrder::with(['product', 'customer', 'qcLogs'])
            ->where('status', 'qc_phase')
            ->get();

        $colCompleted = WorkOrder::with(['product', 'customer'])
            ->where('status', 'completed')
            ->orderBy('completion_date', 'desc')
            ->take(10)
            ->get();

        return view('production.kanban', compact(
            'products',
            'customers',
            'colAntrian',
            'colSlep',
            'colBubut',
            'colQc',
            'colCompleted'
        ));
    }

    public function storeWorkOrder(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'customer_id' => ['nullable', 'exists:customers,id'],
            'target_quantity' => ['required', 'integer', 'min:1'],
            'priority' => ['required', 'in:low,normal,high,urgent'],
            'start_date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:start_date'],
            'notes' => ['nullable', 'string'],
        ]);

        $spkNumber = \App\Services\CodeGeneratorService::generateSpkNumber();

        DB::transaction(function () use ($validated, $spkNumber) {
            $wo = WorkOrder::create([
                'spk_number' => $spkNumber,
                'product_id' => $validated['product_id'],
                'customer_id' => $validated['customer_id'] ?? null,
                'target_quantity' => $validated['target_quantity'],
                'completed_quantity' => 0,
                'scrap_quantity' => 0,
                'status' => 'scheduled',
                'priority' => $validated['priority'],
                'start_date' => $validated['start_date'],
                'due_date' => $validated['due_date'],
                'notes' => $validated['notes'] ?? null,
                'created_by' => Auth::id() ?? 1,
            ]);

            // PRD-07 SOLVED: Create standard 5 steps matching database schema and workshop flow
            ProductionStep::create([
                'work_order_id' => $wo->id,
                'step_name' => 'pembelahan_bongkahan',
                'sequence_order' => 1,
                'machine_number' => 'Mesin Sawmill Pembelah',
                'input_qty' => $wo->target_quantity,
                'status' => 'pending',
            ]);

            ProductionStep::create([
                'work_order_id' => $wo->id,
                'step_name' => 'pemotongan_slep',
                'sequence_order' => 2,
                'machine_number' => 'Mesin Slep Utama',
                'input_qty' => $wo->target_quantity,
                'status' => 'pending',
            ]);

            ProductionStep::create([
                'work_order_id' => $wo->id,
                'step_name' => 'pembubutan_bentuk',
                'sequence_order' => 3,
                'machine_number' => 'Mesin Bubut 1-4',
                'input_qty' => $wo->target_quantity,
                'status' => 'pending',
            ]);

            ProductionStep::create([
                'work_order_id' => $wo->id,
                'step_name' => 'penghalusan_poles',
                'sequence_order' => 4,
                'machine_number' => 'Mesin Bubut Poles',
                'input_qty' => $wo->target_quantity,
                'status' => 'pending',
            ]);

            ProductionStep::create([
                'work_order_id' => $wo->id,
                'step_name' => 'inspeksi_qc',
                'sequence_order' => 5,
                'machine_number' => 'Meja QC 2-Tahap',
                'input_qty' => $wo->target_quantity,
                'status' => 'pending',
            ]);
        });

        return redirect()->route('production.kanban')->with('success', 'SPK Produksi ' . $spkNumber . ' berhasil diterbitkan.');
    }

    public function updateStatus(Request $request, WorkOrder $workOrder)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:draft,scheduled,in_progress,qc_phase,completed,cancelled'],
            'step' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated, $workOrder, $request) {
            $isBecomingCompleted = ($validated['status'] === 'completed' && $workOrder->status !== 'completed' && !$workOrder->completion_date);

            $updateData = ['status' => $validated['status']];

            // PRD-02 SOLVED: Protected from double increment
            if ($isBecomingCompleted) {
                $updateData['completion_date'] = now()->toDateString();
                $completedQty = max(0, $workOrder->target_quantity - $workOrder->scrap_quantity);
                $updateData['completed_quantity'] = $completedQty;

                $workOrder->product->increment('ready_stock', $completedQty);
            }

            $workOrder->update($updateData);

            // Advance steps accordingly
            if ($request->input('step') === 'slep' || ($validated['status'] === 'in_progress' && !$request->filled('step'))) {
                $workOrder->steps()->where('step_name', 'pemotongan_slep')->update(['status' => 'running']);
                $workOrder->steps()->where('step_name', 'pembubutan_bentuk')->update(['status' => 'pending']);
            } elseif ($request->input('step') === 'bubut') {
                $workOrder->steps()->where('step_name', 'pemotongan_slep')->update(['status' => 'completed']);
                $workOrder->steps()->where('step_name', 'pembubutan_bentuk')->update(['status' => 'running']);
            } elseif ($validated['status'] === 'qc_phase') {
                $workOrder->steps()->where('step_name', 'pemotongan_slep')->update(['status' => 'completed']);
                $workOrder->steps()->where('step_name', 'pembubutan_bentuk')->update(['status' => 'completed']);
                $workOrder->steps()->where('step_name', 'penghalusan_poles')->update(['status' => 'running']);
            } elseif ($validated['status'] === 'completed') {
                $workOrder->steps()->update(['status' => 'completed']);
            }
        });

        return redirect()->route('production.kanban')->with('success', 'Status SPK ' . $workOrder->spk_number . ' berhasil diperbarui.');
    }

    public function updateWipProgress(Request $request, WorkOrder $workOrder)
    {
        $validated = $request->validate([
            'completed_quantity' => ['required', 'integer', 'min:0'],
            'scrap_quantity' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:draft,scheduled,in_progress,qc_phase,completed,cancelled'],
            'machine_station' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated, $workOrder) {
            $isBecomingCompleted = ($validated['status'] === 'completed' && $workOrder->status !== 'completed' && !$workOrder->completion_date);

            $updateData = [
                'completed_quantity' => $validated['completed_quantity'],
                'scrap_quantity' => $validated['scrap_quantity'] ?? $workOrder->scrap_quantity,
                'status' => $validated['status'],
            ];

            if (!empty($validated['notes'])) {
                $updateData['notes'] = $validated['notes'];
            }

            // PRD-02 SOLVED: Double increment guarded
            if ($isBecomingCompleted) {
                $updateData['completion_date'] = now()->toDateString();
                $workOrder->product->increment('ready_stock', $validated['completed_quantity']);
            }

            $workOrder->update($updateData);

            if ($validated['status'] === 'completed') {
                $workOrder->steps()->update(['status' => 'completed']);
            }
        });

        return redirect()->back()->with('success', 'Progres WIP untuk SPK ' . $workOrder->spk_number . ' berhasil diperbarui.');
    }

    public function wip()
    {
        return $this->wipTracking();
    }

    public function wipTracking()
    {
        $workOrders = WorkOrder::with(['product', 'steps.operator'])
            ->whereIn('status', ['scheduled', 'in_progress', 'qc_phase'])
            ->get();

        return view('production.wip', compact('workOrders'));
    }
}

