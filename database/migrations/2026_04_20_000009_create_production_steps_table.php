<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('production_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained('work_orders')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('step_name', ['pembelahan_bongkahan', 'pemotongan_slep', 'pembubutan_bentuk', 'penghalusan_poles', 'inspeksi_qc']);
            $table->integer('sequence_order')->default(1);
            $table->string('machine_number', 30)->nullable()->comment('Mesin Slep / Mesin Bubut 1 s.d 7');
            $table->foreignId('operator_id')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->integer('duration_minutes')->default(0);
            $table->integer('input_qty')->default(0);
            $table->integer('output_qty')->default(0);
            $table->enum('status', ['pending', 'running', 'completed'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['work_order_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_steps');
    }
};
