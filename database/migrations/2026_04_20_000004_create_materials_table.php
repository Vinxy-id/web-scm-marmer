<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete()->cascadeOnUpdate();
            $table->string('material_code', 50)->unique();
            $table->string('name', 150);
            $table->enum('type', ['marmer', 'onix', 'batu_kali', 'bahan_penolong'])->default('marmer');
            $table->enum('grade', ['grade_a_super', 'grade_b_standard', 'grade_c_ekonomis'])->default('grade_b_standard');
            $table->string('dimension_info', 100)->nullable()->comment('Dimensi PxLxT cm');
            $table->string('unit', 20)->default('blok');
            $table->decimal('current_stock', 12, 2)->default(0.00);
            $table->decimal('minimum_stock', 12, 2)->default(5.00);
            $table->decimal('unit_cost', 15, 2)->default(0.00);
            $table->timestamps();

            $table->index('material_code');
            $table->index(['current_stock', 'minimum_stock']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
