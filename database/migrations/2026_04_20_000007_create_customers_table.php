<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_code', 50)->unique();
            $table->string('name', 150);
            $table->string('company_name', 150)->nullable();
            $table->string('phone', 20);
            $table->string('email', 100)->nullable();
            $table->text('address');
            $table->string('city', 100);
            $table->enum('customer_type', ['retail', 'kontraktor_arsitektur', 'distributor_ekspor'])->default('retail');
            $table->timestamps();

            $table->index('customer_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
