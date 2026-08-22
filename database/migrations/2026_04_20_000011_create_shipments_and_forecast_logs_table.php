<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Shipments
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('shipment_code', 50)->unique();
            $table->foreignId('work_order_id')->nullable()->constrained('work_orders')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('customer_id')->constrained('customers')->restrictOnDelete()->cascadeOnUpdate();
            $table->string('expedition_name', 100)->comment('Armada Sendiri / Truk Ekspedisi');
            $table->string('tracking_number', 100)->nullable();
            $table->string('driver_name', 100)->nullable();
            $table->string('vehicle_plate', 20)->nullable();
            $table->boolean('packing_verified')->default(false)->comment('1 = Checklist packing kayu lolos');
            $table->date('shipment_date');
            $table->enum('delivery_status', ['packed', 'in_transit', 'delivered', 'returned'])->default('packed');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();

            $table->index('shipment_code');
            $table->index('delivery_status');
        });

        // 2. Tabel Forecasting Logs
        Schema::create('forecasting_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('item_type', ['material', 'product']);
            $table->unsignedBigInteger('item_id');
            $table->string('algorithm_used', 50)->comment('Moving Average, Holt-Winters, ARIMA');
            $table->integer('forecast_horizon_months')->default(3);
            $table->integer('historical_data_points')->default(0);
            $table->decimal('mape_score', 6, 2)->default(0.00)->comment('Akurasi Persentase Error');
            $table->decimal('rmse_score', 10, 2)->default(0.00);
            $table->json('prediction_json')->nullable()->comment('Hasil proyeksi per periode');
            $table->timestamp('generated_at')->nullable()->useCurrent();
            $table->timestamp('created_at')->nullable()->useCurrent();

            $table->index(['item_type', 'item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forecasting_logs');
        Schema::dropIfExists('shipments');
    }
};
