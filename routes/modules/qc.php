<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QcController;
use App\Http\Controllers\WasteController;

Route::prefix('qc')->name('qc.')->group(function () {
    Route::get('/', [QcController::class, 'index'])->name('index');
    Route::post('/inspect', [QcController::class, 'storeInspection'])->name('inspect');
});

Route::prefix('waste')->name('waste.')->group(function () {
    Route::get('/', [WasteController::class, 'index'])->name('index');
    Route::post('/', [WasteController::class, 'store'])->name('store');
});
