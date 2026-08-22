<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete()->cascadeOnUpdate();
            $table->string('product_code', 50)->unique();
            $table->string('name', 150);
            $table->enum('material_type', ['marmer', 'onix', 'batu_kali', 'kombinasi'])->default('marmer');
            $table->string('dimension_spec', 100)->nullable();
            $table->string('finishing_type', 50)->default('Hi-Glossy');
            $table->integer('ready_stock')->default(0);
            $table->integer('safety_stock')->default(5);
            $table->decimal('standard_cogs', 15, 2)->default(0.00);
            $table->decimal('selling_price', 15, 2)->default(0.00);
            $table->string('image_path', 255)->nullable();
            $table->timestamps();

            $table->index('product_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
