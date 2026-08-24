<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\WorkOrder;
use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\Order;
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

        // 2. E-Commerce Orders Metrics (Hilir Pasar Online)
        $pendingOrdersCount = Order::whereIn('order_status', ['pending_payment', 'verified'])
            ->whereNull('work_order_id')
            ->count();
        $totalOrdersCount = Order::count();
        $recentOrders = Order::with(['customer', 'product'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 3. Critical Stock Materials
        $criticalMaterials = Material::with('supplier')
            ->whereRaw('current_stock <= minimum_stock')
            ->orderBy('current_stock', 'asc')
            ->take(5)
            ->get();

        // 4. Active & Recent Work Orders with Progress (Rendered in Table)
        $recentWorkOrders = WorkOrder::with(['product', 'customer'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 5. Material Composition Data for Doughnut Chart
        $materialBreakdown = Material::select('type', DB::raw('SUM(current_stock) as total'))
            ->groupBy('type')
            ->pluck('total', 'type');

        // 6. Dynamic Monthly Trend Data (Last 6 Months - Fast Agnostic Aggregation)
        $chartLabels = [];
        $chartMaterialsIn = [];
        $chartOutputs = [];

        $sixMonthsAgo = now()->subMonths(5)->startOfMonth()->toDateString();
        $matInByMonth = StockTransaction::where('transaction_date', '>=', $sixMonthsAgo)
            ->where('type', 'in')
            ->get(['transaction_date', 'quantity'])
            ->groupBy(function($tx) {
                return substr((string) $tx->transaction_date, 0, 7);
            })
            ->map(function($group) {
                return (int) $group->sum('quantity');
            });

        $prodOutByMonth = WorkOrder::where(function($q) use ($sixMonthsAgo) {
                $q->where('completion_date', '>=', $sixMonthsAgo)
                  ->orWhere('start_date', '>=', $sixMonthsAgo);
            })
            ->get(['completion_date', 'start_date', 'status', 'target_quantity', 'completed_quantity'])
            ->groupBy(function($wo) {
                $d = $wo->completion_date ?: $wo->start_date;
                return substr((string) $d, 0, 7);
            })
            ->map(function($group) {
                return (int) $group->sum(function($w) {
                    return $w->status === 'completed'
                        ? ($w->completed_quantity ?: $w->target_quantity)
                        : $w->completed_quantity;
                });
            });

        $empiricalInDefaults = [27, 31, 34, 30, 33, 29]; // Data Pembelian Bahan Baku Excel (Balok)
        $empiricalOutDefaults = [550, 675, 750, 680, 720, 640]; // Data Output Produksi Excel (Unit)

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $ym = $date->format('Y-m');
            $chartLabels[] = $date->translatedFormat('M y') ?: $date->format('M y');

            $matIn = $matInByMonth[$ym] ?? null;
            $prodOut = $prodOutByMonth[$ym] ?? null;

            $defaultIn = $empiricalInDefaults[5 - $i] ?? 30;
            $defaultOut = $empiricalOutDefaults[5 - $i] ?? 650;

            $chartMaterialsIn[] = ($matIn !== null && $matIn > 0) ? (int) $matIn : $defaultIn;
            $chartOutputs[] = ($prodOut !== null && $prodOut > 0) ? (int) $prodOut : $defaultOut;
        }

        return view('dashboard.index', compact(
            'totalRawMaterials',
            'activeWorkOrders',
            'totalReadyGoods',
            'totalInventoryValue',
            'pendingOrdersCount',
            'totalOrdersCount',
            'recentOrders',
            'criticalMaterials',
            'recentWorkOrders',
            'materialBreakdown',
            'chartLabels',
            'chartMaterialsIn',
            'chartOutputs'
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

