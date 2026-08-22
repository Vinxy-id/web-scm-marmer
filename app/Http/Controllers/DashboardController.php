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
        $totalRawStock = Material::whereIn('type', ['marmer', 'onix', 'batu_kali'])->sum('current_stock');
        $criticalCount = Material::whereRaw('current_stock <= minimum_stock')->count();

        $activeWorkOrders = WorkOrder::whereIn('status', ['scheduled', 'in_progress', 'qc_phase'])->count();
        $activeBatchQty = WorkOrder::whereIn('status', ['scheduled', 'in_progress', 'qc_phase'])->sum('target_quantity');
        $completedWorkOrders = WorkOrder::where('status', 'completed')->count();

        $readyProductsCount = Product::where('ready_stock', '>', 0)->count();
        $totalReadyStock = Product::sum('ready_stock');

        $materialValue = Material::select(DB::raw('SUM(current_stock * unit_cost) as total'))->value('total') ?? 0;
        $productValue = Product::select(DB::raw('SUM(ready_stock * standard_cogs) as total'))->value('total') ?? 0;
        $totalInventoryValue = $materialValue + $productValue;

        $activeShipments = DB::table('shipments')->whereIn('delivery_status', ['packed', 'in_transit'])->count();
        $deliveredShipments = DB::table('shipments')->where('delivery_status', 'delivered')->count();

        $qcLogsCount = DB::table('qc_logs')->count();
        $wasteLogsWeight = DB::table('waste_logs')->sum('weight_kg');

        return view('dashboard.supply-chain-flow', compact(
            'materialsCount',
            'totalRawStock',
            'criticalCount',
            'activeWorkOrders',
            'activeBatchQty',
            'completedWorkOrders',
            'readyProductsCount',
            'totalReadyStock',
            'totalInventoryValue',
            'activeShipments',
            'deliveredShipments',
            'qcLogsCount',
            'wasteLogsWeight'
        ));
    }

    public function reports()
    {
        $driver = DB::connection()->getDriverName();
        $monthExpr = $driver === 'sqlite'
            ? "CAST(strftime('%m', transaction_date) AS INTEGER)"
            : "MONTH(transaction_date)";

        $yearExpr = $driver === 'sqlite'
            ? "strftime('%Y', transaction_date)"
            : "YEAR(transaction_date)";

        $monthlyTransactions = StockTransaction::select(
            DB::raw("{$monthExpr} as month"),
            DB::raw('SUM(CASE WHEN type = "in" THEN quantity ELSE 0 END) as total_in'),
            DB::raw('SUM(CASE WHEN type = "out" THEN quantity ELSE 0 END) as total_out')
        )
        ->whereRaw("{$yearExpr} = ?", [date('Y')])
        ->groupBy(DB::raw($monthExpr))
        ->orderBy(DB::raw($monthExpr), 'asc')
        ->get();

        return view('dashboard.reports', compact('monthlyTransactions'));
    }
}

