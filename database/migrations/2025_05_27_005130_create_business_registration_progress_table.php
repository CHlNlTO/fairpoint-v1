<?php
// database/migrations/2024_01_01_000021_create_business_registration_progress_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_registration_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->string('step_code'); // e.g., 'basic_info', 'tax_info', 'registrations', etc.
            $table->json('data')->nullable(); // Store step-specific data
            $table->boolean('is_completed')->default(false);
            $table->datetime('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');

            $table->unique(['business_id', 'step_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_registration_progress');
    }
};
