<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50)->unique();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('work_order_id')->nullable()->constrained('work_orders')->nullOnDelete()->cascadeOnUpdate();
            $table->integer('quantity')->default(1);
            $table->enum('payment_scheme', ['dp_50', 'full_100'])->default('dp_50');
            $table->enum('payment_method', ['qris', 'bank_bca', 'bank_bri', 'bank_mandiri'])->default('qris');
            $table->decimal('unit_price', 15, 2);
            $table->decimal('total_amount', 15, 2);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->integer('unique_code')->default(0);
            $table->enum('payment_status', ['unpaid', 'paid_dp', 'paid_full', 'refunded'])->default('unpaid');
            $table->enum('order_status', [
                'pending_payment', 
                'verified', 
                'in_production', 
                'qc_phase', 
                'packing', 
                'shipped', 
                'delivered', 
                'cancelled', 
                'expired'
            ])->default('pending_payment');
            $table->text('shipping_address');
            $table->string('shipping_city', 100);
            $table->string('receiver_name', 150);
            $table->string('receiver_phone', 25);
            $table->text('custom_notes')->nullable();
            $table->string('cancellation_reason', 255)->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index('order_number');
            $table->index('payment_status');
            $table->index('order_status');
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
