<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('property_types', function (Blueprint $table) {
            // has_rooms alanından sonra varsayılan olarak true (1) olan is_active alanını ekliyoruz
            $table->boolean('is_active')->default(true)->after('has_rooms');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('property_types', function (Blueprint $table) {
            // Rollback yapılırsa bu kolonu siler
            $table->dropColumn('is_active');
        });
    }
};