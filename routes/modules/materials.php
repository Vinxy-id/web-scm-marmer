<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MaterialController;

Route::prefix('materials')->name('materials.')->group(function () {
    Route::get('/', [MaterialController::class, 'index'])->name('index');
    Route::post('/', [MaterialController::class, 'store'])->name('store');
    Route::get('/{material}/edit', [MaterialController::class, 'edit'])->name('edit');
    Route::put('/{material}', [MaterialController::class, 'update'])->name('update');
    Route::delete('/{material}', [MaterialController::class, 'destroy'])->name('destroy');
    Route::post('/transaction', [MaterialController::class, 'recordTransaction'])->name('transaction');
});
