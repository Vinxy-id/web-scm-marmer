<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('products', 'ikm_name')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('ikm_name', 100)->default('UD Cahaya Onix')->after('name');
                $table->index('ikm_name');
            });
        }

        // Backfill existing data based on known empirical store assignments
        DB::table('products')
            ->where('material_type', 'batu_kali')
            ->orWhere('product_code', 'like', '%-PA-%')
            ->orWhere('name', 'like', '%putra abadi%')
            ->orWhere('name', 'like', '%kali%')
            ->orWhere('name', 'like', '%stepping%')
            ->orWhere('name', 'like', '%batu kali%')
            ->update(['ikm_name' => 'UD Putra Abadi']);

        DB::table('products')
            ->whereNull('ikm_name')
            ->orWhere('ikm_name', '')
            ->update(['ikm_name' => 'UD Cahaya Onix']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['ikm_name']);
            $table->dropColumn('ikm_name');
        });
    }
};
