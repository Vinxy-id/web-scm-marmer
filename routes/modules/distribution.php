<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DistributionController;

Route::prefix('distribution')->name('distribution.')->group(function () {
    Route::get('/', [DistributionController::class, 'index'])->name('index');
    Route::post('/shipment', [DistributionController::class, 'storeShipment'])->name('shipment.store');
    Route::patch('/shipment/{shipment}/status', [DistributionController::class, 'updateShipmentStatus'])->name('shipment.update-status');
});
