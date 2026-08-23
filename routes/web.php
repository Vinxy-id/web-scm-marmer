<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PublicCatalogController;
use App\Http\Controllers\CheckoutController;

/*
|--------------------------------------------------------------------------
| Web Routes - E-SCM Marmer Tulungagung
|--------------------------------------------------------------------------
| Sesuai Dokumen Pembagian Tugas: Docs/PEMBAGIAN_TUGAS_MIGRASI.md
*/

// Public Front-End Landing Page & Product Catalog Showcase
Route::get('/', [PublicCatalogController::class, 'index'])->name('home');
Route::get('/katalog', [PublicCatalogController::class, 'catalog'])->name('catalog');
Route::get('/katalog/{id}', [PublicCatalogController::class, 'show'])->name('catalog.show');

// E-Commerce Direct Checkout, Digital Invoice & Order Tracking (Protected with Anti-Spam Rate Limiter)
Route::get('/checkout/{id}', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store')->middleware('throttle:5,10');
Route::get('/order/invoice/{orderNumber}', [CheckoutController::class, 'invoice'])->name('checkout.invoice');
Route::get('/lacak-pesanan', [CheckoutController::class, 'tracking'])->name('order.tracking');

// Admin E-Commerce Order Management & 2-Gate SPK Verification
Route::prefix('orders')->name('orders.')->group(function () {
    Route::get('/', [\App\Http\Controllers\AdminOrderController::class, 'index'])->name('index');
    Route::post('/{order}/verify-spk', [\App\Http\Controllers\AdminOrderController::class, 'verifyAndGenerateSpk'])->name('verify-spk');
    Route::post('/{order}/cancel', [\App\Http\Controllers\AdminOrderController::class, 'cancel'])->name('cancel');
    Route::delete('/{order}', [\App\Http\Controllers\AdminOrderController::class, 'destroy'])->name('destroy');
});

// Admin / Owner Product Management (CRUD Master Produk)
Route::resource('products', \App\Http\Controllers\ProductController::class)->except(['create', 'show', 'edit']);

// Load routes per modul
require __DIR__ . '/modules/auth.php';
require __DIR__ . '/modules/materials.php';    // Dikelola Alvin
require __DIR__ . '/modules/production.php';   // Dikelola Alvin
require __DIR__ . '/modules/qc.php';           // Dikelola Dapin
require __DIR__ . '/modules/distribution.php'; // Dikelola Dapin
require __DIR__ . '/modules/analytics.php';    // Dikelola Dapin

// Helper endpoint to auto-migrate & seed cloud database
Route::get('/auto-migrate-seed', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $migrate = \Illuminate\Support\Facades\Artisan::output();

        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        $seed = \Illuminate\Support\Facades\Artisan::output();

        return response()->json([
            'status' => 'success',
            'message' => 'Database TiDB Cloud berhasil di-migrasi dan di-seed!',
            'migration_log' => $migrate,
            'seeder_log' => $seed,
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ], 500);
    }
});

