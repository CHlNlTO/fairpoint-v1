<?php
// database/migrations/2024_01_01_000025_update_business_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('business_users', function (Blueprint $table) {
            $table->unsignedBigInteger('status_id')->after('user_id')->nullable();

            $table->foreign('status_id')->references('id')->on('business_user_statuses')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('business_users', function (Blueprint $table) {
            $table->dropForeign(['status_id']);
            $table->dropColumn('status_id');
        });
    }
};
