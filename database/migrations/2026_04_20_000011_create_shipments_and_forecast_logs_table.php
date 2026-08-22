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
            $table->string('shipment_number', 50)->unique();
            $table->foreignId('customer_id')->constrained('customers')->restrictOnDelete()->cascadeOnUpdate();
            $table->date('shipment_date');
            $table->string('expedition_name', 100)->nullable();
            $table->string('vehicle_number', 30)->nullable();
            $table->string('driver_name', 100)->nullable();
            $table->boolean('wooden_packing_checked')->default(true)->comment('Verifikasi packing krat kayu');
            $table->enum('status', ['prepared', 'in_transit', 'delivered', 'cancelled'])->default('prepared');
            $table->string('tracking_number', 100)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();

            $table->index('shipment_number');
            $table->index('status');
        });

        // 2. Tabel Forecasting Logs
        Schema::create('forecasting_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->nullable()->constrained('materials')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete()->cascadeOnUpdate();
            $table->enum('model_type', ['moving_average', 'single_exp_smoothing', 'holt_winters'])->default('holt_winters');
            $table->date('period_start');
            $table->date('period_end');
            $table->integer('horizon_months')->default(3);
            $table->json('input_data_json')->nullable();
            $table->json('forecast_result_json')->nullable();
            $table->decimal('mape_score', 8, 4)->nullable();
            $table->decimal('rmse_score', 12, 4)->nullable();
            $table->foreignId('generated_by')->constrained('users')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();

            $table->index('model_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forecasting_logs');
        Schema::dropIfExists('shipments');
    }
};
