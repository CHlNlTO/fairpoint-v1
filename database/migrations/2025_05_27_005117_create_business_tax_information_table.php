<?php
// database/migrations/2024_01_01_000019_create_business_tax_information_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_tax_information', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('income_tax_type_id')->nullable();
            $table->unsignedBigInteger('business_tax_type_id')->nullable();
            $table->boolean('with_1601c')->default(false);
            $table->boolean('with_ewt')->default(false);
            $table->boolean('tamp')->default(false);
            $table->timestamps();

            $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');
            $table->foreign('income_tax_type_id')->references('id')->on('income_tax_types')->onDelete('set null');
            $table->foreign('business_tax_type_id')->references('id')->on('business_tax_types')->onDelete('set null');

            $table->unique('business_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_tax_information');
    }
};
