<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForecastingController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/supply-chain-flow', [DashboardController::class, 'supplyChainFlow'])->name('supply-chain-flow');
Route::get('/reports', [DashboardController::class, 'reports'])->name('reports');

Route::prefix('forecasting')->name('forecasting.')->group(function () {
    Route::get('/', [ForecastingController::class, 'index'])->name('index');
    Route::post('/calculate', [ForecastingController::class, 'calculate'])->name('calculate');
});
