<?php
// database\migrations\2025_02_26_011750_create_journal_entries_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->string('journal_number');
            $table->date('transaction_date');
            $table->string('name')->nullable();
            $table->text('particulars');
            $table->decimal('amount', 12, 2);
            $table->unsignedBigInteger('account_id');
            $table->string('created_by')->nullable();
            $table->timestamps();

            $table->foreign('account_id')
                ->references('id')
                ->on('chart_of_accounts')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journal_entries');
    }
};
