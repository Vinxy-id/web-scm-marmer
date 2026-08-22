<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\QcController;
use App\Http\Controllers\WasteController;
use App\Http\Controllers\DistributionController;
use App\Http\Controllers\ForecastingController;

/*
|--------------------------------------------------------------------------
| Web Routes - E-SCM Marmer Tulungagung
|--------------------------------------------------------------------------
*/

// Redirect root to dashboard (or login if not authenticated)
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes (Session Auth & RBAC)
Route::middleware(['web'])->group(function () {
    
    // 1. Dashboard Eksekutif & Alur SCM (Semua Role)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/supply-chain-flow', [DashboardController::class, 'supplyChainFlow'])->name('supply-chain-flow');

    // 2. Modul Bahan Baku & Pengadaan (Admin, Owner, Gudang)
    Route::prefix('materials')->name('materials.')->group(function () {
        Route::get('/', [MaterialController::class, 'index'])->name('index');
        Route::post('/', [MaterialController::class, 'store'])->name('store');
        Route::get('/{material}/edit', [MaterialController::class, 'edit'])->name('edit');
        Route::put('/{material}', [MaterialController::class, 'update'])->name('update');
        Route::delete('/{material}', [MaterialController::class, 'destroy'])->name('destroy');
        Route::post('/transaction', [MaterialController::class, 'recordTransaction'])->name('transaction');
    });

    // 3. Modul Produksi & Kanban SPK (Admin, Owner, Produksi)
    Route::prefix('production')->name('production.')->group(function () {
        Route::get('/', [ProductionController::class, 'index'])->name('index');
        Route::get('/kanban', [ProductionController::class, 'kanban'])->name('kanban');
        Route::post('/work-order', [ProductionController::class, 'storeWorkOrder'])->name('work-order.store');
        Route::patch('/work-order/{workOrder}/status', [ProductionController::class, 'updateStatus'])->name('work-order.update-status');
        Route::get('/wip', [ProductionController::class, 'wipTracking'])->name('wip');
    });

    // 4. Modul Quality Control (QC 2-Tahap)
    Route::prefix('qc')->name('qc.')->group(function () {
        Route::get('/', [QcController::class, 'index'])->name('index');
        Route::post('/inspect', [QcController::class, 'storeInspection'])->name('inspect');
    });

    // 5. Modul Residu & Hilirisasi Limbah (UD Putra Abadi)
    Route::prefix('waste')->name('waste.')->group(function () {
        Route::get('/', [WasteController::class, 'index'])->name('index');
        Route::post('/', [WasteController::class, 'store'])->name('store');
    });

    // 6. Modul Distribusi & Packing
    Route::prefix('distribution')->name('distribution.')->group(function () {
        Route::get('/', [DistributionController::class, 'index'])->name('index');
        Route::post('/shipment', [DistributionController::class, 'storeShipment'])->name('shipment.store');
        Route::patch('/shipment/{shipment}/status', [DistributionController::class, 'updateShipmentStatus'])->name('shipment.update-status');
    });

    // 7. Modul Peramalan AI & Forecasting (Python API Integration)
    Route::prefix('forecasting')->name('forecasting.')->group(function () {
        Route::get('/', [ForecastingController::class, 'index'])->name('index');
        Route::post('/calculate', [ForecastingController::class, 'calculate'])->name('calculate');
    });

    // 8. Laporan & Pengaturan
    Route::get('/reports', [DashboardController::class, 'reports'])->name('reports');
});
