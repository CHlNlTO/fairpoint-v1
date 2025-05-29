<?php
// database/migrations/2024_01_01_000012_create_sub_account_types_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_account_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->unsignedBigInteger('account_type_id');
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('account_type_id')->references('id')->on('account_types')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_account_types');
    }
};
