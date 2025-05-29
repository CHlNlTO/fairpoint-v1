<?php
// database/seeders/AccountTypeSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'Asset',
                'code' => 'ASSET',
                'description' => 'Resources owned by the business',
                'sort_order' => 1,
            ],
            [
                'name' => 'Liability',
                'code' => 'LIABILITY',
                'description' => 'Obligations owed by the business',
                'sort_order' => 2,
            ],
            [
                'name' => 'Equity',
                'code' => 'EQUITY',
                'description' => 'Owner\'s interest in the business',
                'sort_order' => 3,
            ],
            [
                'name' => 'Revenue',
                'code' => 'REVENUE',
                'description' => 'Income earned by the business',
                'sort_order' => 4,
            ],
            [
                'name' => 'Expense',
                'code' => 'EXPENSE',
                'description' => 'Costs incurred by the business',
                'sort_order' => 5,
            ],
            [
                'name' => 'COGS',
                'code' => 'COGS',
                'description' => 'Cost of Goods Sold',
                'sort_order' => 6,
            ],
        ];

        foreach ($types as $type) {
            DB::table('account_types')->insert([
                'name' => $type['name'],
                'code' => $type['code'],
                'description' => $type['description'],
                'sort_order' => $type['sort_order'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
