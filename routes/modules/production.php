<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductionController;

Route::prefix('production')->name('production.')->group(function () {
    Route::get('/', [ProductionController::class, 'index'])->name('index');
    Route::get('/kanban', [ProductionController::class, 'kanban'])->name('kanban');
    Route::post('/work-order', [ProductionController::class, 'storeWorkOrder'])->name('work-order.store');
    Route::patch('/work-order/{workOrder}/status', [ProductionController::class, 'updateStatus'])->name('work-order.update-status');
    Route::patch('/work-order/{workOrder}/wip-progress', [ProductionController::class, 'updateWipProgress'])->name('work-order.update-wip');
    Route::get('/wip', [ProductionController::class, 'wipTracking'])->name('wip');
});
