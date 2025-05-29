<?php
// database/migrations/2024_01_01_000005_create_registration_types_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registration_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // BIR, DTI, SEC, LGU, CDA
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->boolean('requires_expiry')->default(false);
            $table->boolean('requires_document')->default(true);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_types');
    }
};
