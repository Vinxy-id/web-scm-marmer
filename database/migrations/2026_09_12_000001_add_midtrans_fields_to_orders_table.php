<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('snap_token', 255)->nullable()->after('payment_method');
            $table->string('midtrans_transaction_id', 100)->nullable()->after('snap_token');
            $table->string('midtrans_payment_type', 50)->nullable()->after('midtrans_transaction_id');
            $table->string('midtrans_status', 50)->nullable()->after('midtrans_payment_type');
            $table->json('midtrans_response')->nullable()->after('midtrans_status');
        });

        // Update payment_method enum to regular varchar string or include 'midtrans'
        // Using DB statement for safe alter on MySQL/MariaDB/TiDB
        try {
            DB::statement("ALTER TABLE orders MODIFY COLUMN payment_method VARCHAR(50) NOT NULL DEFAULT 'midtrans'");
        } catch (\Throwable $e) {
            // Fallback for sqlite/other drivers
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'snap_token',
                'midtrans_transaction_id',
                'midtrans_payment_type',
                'midtrans_status',
                'midtrans_response',
            ]);
        });
    }
};
