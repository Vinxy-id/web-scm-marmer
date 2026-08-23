<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_code', 50)->unique();
            $table->string('name', 150);
            $table->string('contact_person', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('quarry_location', 150)->nullable()->comment('Lokasi Tambang: Campurdarat, dll');
            $table->string('material_category', 100)->nullable();
            $table->timestamps();

            $table->index('supplier_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
