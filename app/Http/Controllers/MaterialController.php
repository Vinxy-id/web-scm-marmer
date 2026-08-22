<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Supplier;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = Material::with('supplier');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            if ($request->status === 'kritis') {
                $query->whereRaw('current_stock <= minimum_stock * 0.8');
            } elseif ($request->status === 'rendah') {
                $query->whereRaw('current_stock > minimum_stock * 0.8 AND current_stock <= minimum_stock');
            } elseif ($request->status === 'normal') {
                $query->whereRaw('current_stock > minimum_stock');
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('material_code', 'like', "%{$search}%");
            });
        }

        $materials = $query->paginate(10)->withQueryString();
        $suppliers = Supplier::all();

        return view('materials.index', compact('materials', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'material_code' => ['required', 'string', 'max:50', 'unique:materials,material_code'],
            'name' => ['required', 'string', 'max:150'],
            'type' => ['required', 'in:marmer,onix,batu_kali,bahan_penolong'],
            'grade' => ['required', 'in:grade_a_super,grade_b_standard,grade_c_ekonomis'],
            'dimension_info' => ['nullable', 'string', 'max:100'],
            'unit' => ['required', 'string', 'max:20'],
            'current_stock' => ['required', 'numeric', 'min:0'],
            'minimum_stock' => ['required', 'numeric', 'min:0'],
            'unit_cost' => ['required', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($validated) {
            $material = Material::create($validated);

            if ($material->current_stock > 0) {
                StockTransaction::create([
                    'transaction_code' => 'TX-OPN-' . time(),
                    'material_id' => $material->id,
                    'user_id' => Auth::id() ?? 1,
                    'type' => 'opening',
                    'quantity' => $material->current_stock,
                    'unit' => $material->unit,
                    'before_stock' => 0,
                    'after_stock' => $material->current_stock,
                    'notes' => 'Stok Awal Inisialisasi Bahan Baku',
                    'transaction_date' => now()->toDateString(),
                ]);
            }
        });

        return redirect()->route('materials.index')->with('success', 'Bahan baku baru berhasil ditambahkan.');
    }

    public function recordTransaction(Request $request)
    {
        $validated = $request->validate([
            'material_id' => ['required', 'exists:materials,id'],
            'type' => ['required', 'in:in,out'],
            'quantity' => ['required', 'numeric', 'min:0.01'],
            'notes' => ['nullable', 'string'],
        ]);

        $material = Material::findOrFail($validated['material_id']);
        if ($validated['type'] === 'out' && $material->current_stock < $validated['quantity']) {
            return back()->withErrors(['quantity' => 'Stok bahan baku tidak mencukupi untuk transaksi keluar.'])->withInput();
        }

        DB::transaction(function () use ($validated, $material) {
            $beforeStock = $material->current_stock;
            $afterStock = ($validated['type'] === 'in')
                ? $beforeStock + $validated['quantity']
                : $beforeStock - $validated['quantity'];

            $material->update(['current_stock' => $afterStock]);

            StockTransaction::create([
                'transaction_code' => 'TX-' . strtoupper($validated['type']) . '-' . time(),
                'material_id' => $material->id,
                'user_id' => Auth::id() ?? 1,
                'type' => $validated['type'],
                'quantity' => $validated['quantity'],
                'unit' => $material->unit,
                'before_stock' => $beforeStock,
                'after_stock' => $afterStock,
                'notes' => $validated['notes'] ?? ($validated['type'] === 'in' ? 'Penerimaan Bongkahan Tambang' : 'Pengambilan Lantai Produksi'),
                'transaction_date' => now()->toDateString(),
            ]);
        });

        return redirect()->route('materials.index')->with('success', 'Transaksi mutasi stok berhasil dicatat.');
    }

    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'name' => ['required', 'string', 'max:150'],
            'type' => ['required', 'in:marmer,onix,batu_kali,bahan_penolong'],
            'grade' => ['required', 'in:grade_a_super,grade_b_standard,grade_c_ekonomis'],
            'dimension_info' => ['nullable', 'string', 'max:100'],
            'minimum_stock' => ['required', 'numeric', 'min:0'],
            'unit_cost' => ['required', 'numeric', 'min:0'],
        ]);

        $material->update($validated);

        return redirect()->route('materials.index')->with('success', 'Data bahan baku ' . $material->material_code . ' berhasil diperbarui.');
    }

    public function destroy(Material $material)
    {
        $code = $material->material_code;
        $material->delete();

        return redirect()->route('materials.index')->with('success', 'Bahan baku ' . $code . ' berhasil dihapus.');
    }
}
