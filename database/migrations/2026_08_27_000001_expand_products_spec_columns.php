<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint ) {
            ->string('dimension_spec', 200)->nullable()->change();
            ->string('finishing_type', 150)->default('Hi-Glossy')->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint ) {
            ->string('dimension_spec', 100)->nullable()->change();
            ->string('finishing_type', 50)->default('Hi-Glossy')->change();
        });
    }
};
