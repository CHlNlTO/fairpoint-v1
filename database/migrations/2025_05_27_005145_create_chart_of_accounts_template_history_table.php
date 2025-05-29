<?php
// database/migrations/2024_01_01_000023_create_chart_of_accounts_template_history_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chart_of_accounts_template_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('template_id');
            $table->string('version');
            $table->json('template_data'); // Complete snapshot of template and items
            $table->string('change_description')->nullable();
            $table->unsignedBigInteger('changed_by');
            $table->timestamps();

            $table->foreign('template_id')->references('id')->on('chart_of_accounts_templates')->onDelete('cascade');
            $table->foreign('changed_by')->references('id')->on('users')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chart_of_accounts_template_history');
    }
};
