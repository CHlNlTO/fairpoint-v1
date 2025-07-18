<?php
// database/migrations/2025_07_04_000002_fix_business_address_columns.php

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
        Schema::table('businesses', function (Blueprint $table) {
            // Drop existing foreign keys
            $table->dropForeign(['barangay_id']);
            $table->dropForeign(['city_id']);
            $table->dropForeign(['province_id']);

            // Change column types to string to match yajra/laravel-address
            $table->string('barangay_id', 20)->nullable()->change();
            $table->string('city_id', 20)->nullable()->change();
            $table->string('province_id', 20)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            // Change back to bigInteger
            $table->unsignedBigInteger('barangay_id')->nullable()->change();
            $table->unsignedBigInteger('city_id')->nullable()->change();
            $table->unsignedBigInteger('province_id')->nullable()->change();

            // Re-add foreign keys
            $table->foreign('barangay_id')->references('id')->on('barangays')->onDelete('set null');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('set null');
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('set null');
        });
    }
};
