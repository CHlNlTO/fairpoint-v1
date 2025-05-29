<?php
// database/migrations/2024_01_01_000014_create_chart_of_accounts_template_items_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chart_of_accounts_template_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('template_id');
            $table->string('code');
            $table->string('name');
            $table->unsignedBigInteger('account_type_id');
            $table->unsignedBigInteger('sub_account_type_id')->nullable();
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('template_id')->references('id')->on('chart_of_accounts_templates')->onDelete('cascade');
            $table->foreign('account_type_id')->references('id')->on('account_types')->onDelete('restrict');
            $table->foreign('sub_account_type_id')->references('id')->on('sub_account_types')->onDelete('restrict');

            $table->unique(['template_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chart_of_accounts_template_items');
    }
};
