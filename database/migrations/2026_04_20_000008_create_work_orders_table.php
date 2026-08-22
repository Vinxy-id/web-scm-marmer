<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->string('spk_number', 50)->unique();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete()->cascadeOnUpdate();
            $table->integer('target_quantity')->default(1);
            $table->integer('completed_quantity')->default(0);
            $table->integer('scrap_quantity')->default(0);
            $table->enum('status', ['draft', 'scheduled', 'in_progress', 'qc_phase', 'completed', 'cancelled'])->default('draft');
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->date('start_date');
            $table->date('due_date');
            $table->date('completion_date')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();

            $table->index('spk_number');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};
