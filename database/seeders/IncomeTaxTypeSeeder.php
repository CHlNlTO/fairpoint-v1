<?php
// database/seeders/IncomeTaxTypeSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IncomeTaxTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'Tax-Exempt',
                'code' => 'TAX_EXEMPT',
                'description' => 'Exempt from income tax',
                'rate' => 0.00,
                'sort_order' => 1,
            ],
            [
                'name' => 'Regular Income Tax',
                'code' => 'REGULAR',
                'description' => 'Standard corporate income tax rate',
                'rate' => 30.00,
                'sort_order' => 2,
            ],
            [
                'name' => 'Preferential Tax Rate',
                'code' => 'PREFERENTIAL',
                'description' => 'Special tax rate for qualified businesses',
                'rate' => null, // Variable rate
                'sort_order' => 3,
            ],
            [
                'name' => 'Minimum Corporate Income Tax (MCIT)',
                'code' => 'MCIT',
                'description' => '2% of gross income',
                'rate' => 2.00,
                'sort_order' => 4,
            ],
        ];

        foreach ($types as $type) {
            DB::table('income_tax_types')->insert([
                'name' => $type['name'],
                'code' => $type['code'],
                'description' => $type['description'],
                'rate' => $type['rate'],
                'sort_order' => $type['sort_order'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
