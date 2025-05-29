<?php
// database/migrations/2024_01_01_000016_create_businesses_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('tin')->nullable();
            $table->string('email')->nullable();

            // Address fields
            $table->string('address_sub_street')->nullable();
            $table->string('address_street')->nullable();
            $table->unsignedBigInteger('barangay_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('province_id')->nullable();
            $table->string('zip_code')->nullable();

            // Business details
            $table->unsignedBigInteger('business_type_id')->nullable();
            $table->unsignedBigInteger('business_structure_id')->nullable();
            $table->unsignedBigInteger('industry_id')->nullable();

            // Fiscal year
            $table->date('fiscal_year_start')->nullable();
            $table->date('fiscal_year_end')->nullable();

            // Status
            $table->unsignedBigInteger('status_id');

            // Tracking
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('barangay_id')->references('id')->on('barangays')->onDelete('set null');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('set null');
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('set null');
            $table->foreign('business_type_id')->references('id')->on('business_types')->onDelete('set null');
            $table->foreign('business_structure_id')->references('id')->on('business_structures')->onDelete('set null');
            $table->foreign('industry_id')->references('id')->on('industries')->onDelete('set null');
            $table->foreign('status_id')->references('id')->on('business_statuses')->onDelete('restrict');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
