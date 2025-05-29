<?php
// database/migrations/2024_01_01_000020_create_business_chart_of_accounts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->string('code');
            $table->string('name');
            $table->unsignedBigInteger('account_type_id');
            $table->unsignedBigInteger('sub_account_type_id')->nullable();
            $table->text('description')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->boolean('is_from_template')->default(false);
            $table->unsignedBigInteger('template_item_id')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');
            $table->foreign('account_type_id')->references('id')->on('account_types')->onDelete('restrict');
            $table->foreign('sub_account_type_id')->references('id')->on('sub_account_types')->onDelete('restrict');
            $table->foreign('template_item_id')->references('id')->on('chart_of_accounts_template_items')->onDelete('set null');

            $table->unique(['business_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_chart_of_accounts');
    }
};
