<?php
// database\migrations\2025_02_26_011530_create_expense_transactions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expense_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number');
            $table->date('transaction_date');
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->string('vendor_name')->nullable(); // For vendors not in the system
            $table->string('tin')->nullable();
            $table->string('address')->nullable();
            $table->text('particulars');
            $table->decimal('amount', 12, 2);
            $table->unsignedBigInteger('account_id'); // Chart of Accounts ID
            $table->unsignedBigInteger('payment_method_id');
            $table->unsignedBigInteger('project_id')->nullable();
            $table->string('quarter')->nullable(); // For quarterly reporting
            $table->year('fiscal_year')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamps();

            $table->foreign('vendor_id')
                ->references('id')
                ->on('vendors')
                ->onDelete('set null');

            $table->foreign('account_id')
                ->references('id')
                ->on('chart_of_accounts')
                ->onDelete('restrict');

            $table->foreign('payment_method_id')
                ->references('id')
                ->on('payment_methods')
                ->onDelete('restrict');

            $table->foreign('project_id')
                ->references('id')
                ->on('projects')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expense_transactions');
    }
};
