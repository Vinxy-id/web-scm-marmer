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
        // 1. Users Table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'owner', 'gudang', 'produksi', 'distribusi'])->default('gudang');
            $table->string('phone', 20)->nullable();
            $table->string('ikm_name', 100)->default('UD Cahaya Onix');
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });

        // 2. Suppliers Table
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_code', 50)->unique();
            $table->string('name', 150);
            $table->string('contact_person', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('quarry_location', 150)->nullable();
            $table->string('material_category', 100)->nullable();
            $table->timestamps();
        });

        // 3. Categories Table
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->enum('type', ['material', 'product'])->default('product');
            $table->string('description', 255)->nullable();
            $table->timestamps();
        });

        // 4. Materials Table
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete()->cascadeOnUpdate();
            $table->string('material_code', 50)->unique();
            $table->string('name', 150);
            $table->enum('type', ['marmer', 'onix', 'batu_kali', 'bahan_penolong'])->default('marmer');
            $table->enum('grade', ['grade_a_super', 'grade_b_standard', 'grade_c_ekonomis'])->default('grade_b_standard');
            $table->string('dimension_info', 100)->nullable();
            $table->string('unit', 20)->default('blok');
            $table->decimal('current_stock', 12, 2)->default(0.00);
            $table->decimal('minimum_stock', 12, 2)->default(5.00);
            $table->decimal('unit_cost', 15, 2)->default(0.00);
            $table->timestamps();
        });

        // 5. Stock Transactions Table
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained('materials')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('transaction_code', 50)->unique();
            $table->enum('type', ['opening', 'in', 'out', 'consign', 'adjustment'])->default('in');
            $table->decimal('quantity', 12, 2);
            $table->decimal('stock_before', 12, 2);
            $table->decimal('stock_after', 12, 2);
            $table->date('transaction_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 6. Products Table
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('product_code', 50)->unique();
            $table->string('name', 150);
            $table->enum('product_type', ['lantai_dinding', 'kerajinan_vas', 'patung_souvenir', 'meja_wastafel', 'custom'])->default('kerajinan_vas');
            $table->string('dimension_spec', 100)->nullable();
            $table->string('unit', 20)->default('pcs');
            $table->decimal('ready_stock', 10, 2)->default(0.00);
            $table->decimal('selling_price', 15, 2)->default(0.00);
            $table->timestamps();
        });

        // 7. Work Orders Table (SPK Produksi)
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('material_id')->constrained('materials');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('spk_number', 50)->unique();
            $table->decimal('target_qty', 10, 2);
            $table->decimal('material_used_qty', 10, 2);
            $table->enum('priority', ['normal', 'urgent'])->default('normal');
            $table->enum('current_status', ['draft', 'slep', 'bubut', 'poles', 'qc', 'completed', 'canceled'])->default('draft');
            $table->date('start_date');
            $table->date('target_completion_date')->nullable();
            $table->date('actual_completion_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 8. Production Steps Tracking Table
        Schema::create('production_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained('work_orders')->cascadeOnDelete();
            $table->foreignId('operator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('station_name', ['pembelahan', 'pemotongan_slep', 'pembubutan_gerinda', 'finishing_poles'])->default('pemotongan_slep');
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->integer('processed_qty')->default(0);
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 9. QC Records Table
        Schema::create('qc_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained('work_orders')->cascadeOnDelete();
            $table->foreignId('inspector_id')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('total_inspected')->default(0);
            $table->integer('passed_qty')->default(0);
            $table->integer('defect_qty')->default(0);
            $table->enum('defect_type', ['retak_rambut', 'pori_batu', 'salah_potong', 'finishing_kasar', 'pecah'])->nullable();
            $table->decimal('waste_weight_kg', 10, 2)->default(0.00);
            $table->dateTime('inspection_date');
            $table->text('corrective_action')->nullable();
            $table->timestamps();
        });

        // 10. Shipments & Packing Checklist Table
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->nullable()->constrained('work_orders')->nullOnDelete();
            $table->string('shipment_code', 50)->unique();
            $table->string('customer_name', 150);
            $table->text('destination_address');
            $table->enum('expedition_type', ['pickup_sendiri', 'truk_lokal', 'ekspedisi_kargo'])->default('truk_lokal');
            $table->boolean('packing_wooden_crate')->default(false);
            $table->boolean('packing_bubble_wrap')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->enum('status', ['preparing', 'packed', 'in_transit', 'delivered'])->default('preparing');
            $table->date('shipped_date')->nullable();
            $table->date('received_date')->nullable();
            $table->timestamps();
        });

        // 11. Forecasting Results Table
        Schema::create('forecasting_results', function (Blueprint $table) {
            $table->id();
            $table->enum('item_type', ['material', 'product'])->default('material');
            $table->unsignedBigInteger('item_id');
            $table->string('forecast_period', 20); // YYYY-MM
            $table->enum('algorithm_used', ['moving_average', 'holt_winters', 'arima'])->default('holt_winters');
            $table->decimal('forecast_qty', 12, 2);
            $table->decimal('lower_bound', 12, 2)->nullable();
            $table->decimal('upper_bound', 12, 2)->nullable();
            $table->decimal('mape_score', 8, 2)->nullable();
            $table->decimal('rmse_score', 12, 2)->nullable();
            $table->dateTime('calculated_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forecasting_results');
        Schema::dropIfExists('shipments');
        Schema::dropIfExists('qc_records');
        Schema::dropIfExists('production_steps');
        Schema::dropIfExists('work_orders');
        Schema::dropIfExists('products');
        Schema::dropIfExists('stock_transactions');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('users');
    }
};
