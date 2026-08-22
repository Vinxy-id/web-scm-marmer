<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\WorkOrder;
use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. KPI Aggregations
        $totalRawMaterials = Material::whereIn('type', ['marmer', 'onix', 'batu_kali'])->sum('current_stock');
        $activeWorkOrders = WorkOrder::whereIn('status', ['scheduled', 'in_progress', 'qc_phase'])->count();
        $totalReadyGoods = Product::sum('ready_stock');
        
        $materialValue = Material::select(DB::raw('SUM(current_stock * unit_cost) as total'))->value('total') ?? 0;
        $productValue = Product::select(DB::raw('SUM(ready_stock * standard_cogs) as total'))->value('total') ?? 0;
        $totalInventoryValue = $materialValue + $productValue;

        // 2. Critical Stock Materials
        $criticalMaterials = Material::with('supplier')
            ->whereRaw('current_stock <= minimum_stock')
            ->orderBy('current_stock', 'asc')
            ->take(5)
            ->get();

        // 3. Active Work Orders with progress
        $recentWorkOrders = WorkOrder::with(['product', 'customer'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 4. Material Composition Data for Chart
        $materialBreakdown = Material::select('type', DB::raw('SUM(current_stock) as total'))
            ->groupBy('type')
            ->pluck('total', 'type');

        return view('dashboard.index', compact(
            'totalRawMaterials',
            'activeWorkOrders',
            'totalReadyGoods',
            'totalInventoryValue',
            'criticalMaterials',
            'recentWorkOrders',
            'materialBreakdown'
        ));
    }

    public function supplyChainFlow()
    {
        $materialsCount = Material::count();
        $workOrdersCount = WorkOrder::count();
        $readyProductsCount = Product::where('ready_stock', '>', 0)->count();

        return view('dashboard.supply-chain-flow', compact(
            'materialsCount',
            'workOrdersCount',
            'readyProductsCount'
        ));
    }

    public function reports()
    {
        $monthlyTransactions = StockTransaction::select(
            DB::raw('MONTH(transaction_date) as month'),
            DB::raw('SUM(CASE WHEN type = "in" THEN quantity ELSE 0 END) as total_in'),
            DB::raw('SUM(CASE WHEN type = "out" THEN quantity ELSE 0 END) as total_out')
        )
        ->whereYear('transaction_date', date('Y'))
        ->groupBy(DB::raw('MONTH(transaction_date)'))
        ->get();

        return view('dashboard.reports', compact('monthlyTransactions'));
    }
}
