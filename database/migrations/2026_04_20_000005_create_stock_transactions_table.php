<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code', 50)->unique();
            $table->foreignId('material_id')->constrained('materials')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete()->cascadeOnUpdate();
            $table->enum('type', ['opening', 'in', 'out', 'consign']);
            $table->decimal('quantity', 12, 2);
            $table->string('unit', 20)->default('blok');
            $table->decimal('before_stock', 12, 2)->default(0.00);
            $table->decimal('after_stock', 12, 2)->default(0.00);
            $table->string('reference_type', 50)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('notes')->nullable();
            $table->date('transaction_date');
            $table->timestamps();

            $table->index('transaction_code');
            $table->index(['type', 'transaction_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_transactions');
    }
};
