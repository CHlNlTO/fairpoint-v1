<?php
// database/migrations/2024_01_01_000018_create_business_registrations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_registrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('registration_type_id');
            $table->boolean('is_registered')->default(false);
            $table->string('registration_number')->nullable();
            $table->string('document_path')->nullable();
            $table->date('registration_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');
            $table->foreign('registration_type_id')->references('id')->on('registration_types')->onDelete('restrict');

            $table->unique(['business_id', 'registration_type_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_registrations');
    }
};
