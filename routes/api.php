<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'app' => config('app.name'),
        'timestamp' => now()->toIso8601String()
    ]);
});

// Midtrans Payment Gateway Webhook Notification
Route::post('/midtrans/notification', [\App\Http\Controllers\Api\MidtransCallbackController::class, 'handle'])->name('midtrans.notification');

