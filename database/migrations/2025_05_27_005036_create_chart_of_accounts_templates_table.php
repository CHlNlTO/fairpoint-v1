<?php
// database/migrations/2024_01_01_000013_create_chart_of_accounts_templates_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chart_of_accounts_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('industry_id');
            $table->unsignedBigInteger('business_structure_id');
            $table->string('version')->default('1.0');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->foreign('industry_id')->references('id')->on('industries')->onDelete('restrict');
            $table->foreign('business_structure_id')->references('id')->on('business_structures')->onDelete('restrict');

            $table->unique(
                ['industry_id', 'business_structure_id', 'version'],
                'coa_template_unique_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chart_of_accounts_templates');
    }
};
