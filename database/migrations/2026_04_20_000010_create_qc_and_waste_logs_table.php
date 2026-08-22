<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel QC Logs
        Schema::create('qc_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained('work_orders')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('step_id')->nullable()->constrained('production_steps')->nullOnDelete()->cascadeOnUpdate();
            $table->enum('stage', ['qc1_raw_shape', 'qc2_final_polish']);
            $table->foreignId('inspector_id')->constrained('users')->restrictOnDelete()->cascadeOnUpdate();
            $table->integer('inspected_quantity')->default(0);
            $table->integer('pass_quantity')->default(0);
            $table->integer('rework_quantity')->default(0);
            $table->integer('scrap_quantity')->default(0);
            $table->string('defect_type', 150)->nullable();
            $table->string('rework_action', 255)->nullable();
            $table->date('inspection_date');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['stage', 'inspection_date']);
        });

        // 2. Tabel Waste Logs
        Schema::create('waste_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained('work_orders')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('step_id')->nullable()->constrained('production_steps')->nullOnDelete()->cascadeOnUpdate();
            $table->enum('waste_type', ['sisa_layak_cladding', 'serbuk_bubut_sludge', 'bongkahan_urukan']);
            $table->decimal('weight_kg', 10, 2)->default(0.00);
            $table->decimal('volume_m3', 10, 3)->nullable()->default(0.000);
            $table->enum('reuse_status', ['disimpan_daur_ulang', 'dijual_ke_pihak3', 'dibuang_ke_urukan'])->default('disimpan_daur_ulang');
            $table->string('notes', 255)->nullable();
            $table->date('logged_at');
            $table->timestamps();

            $table->index(['waste_type', 'reuse_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waste_logs');
        Schema::dropIfExists('qc_logs');
    }
};
