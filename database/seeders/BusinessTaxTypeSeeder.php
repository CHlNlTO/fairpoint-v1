<?php
// database/seeders/BusinessTaxTypeSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessTaxTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'VAT (Value Added Tax)',
                'code' => 'VAT',
                'description' => '12% VAT on goods and services',
                'rate' => 12.00,
                'sort_order' => 1,
            ],
            [
                'name' => 'Non-VAT',
                'code' => 'NON_VAT',
                'description' => 'Not VAT registered',
                'rate' => 0.00,
                'sort_order' => 2,
            ],
            [
                'name' => 'Percentage Tax',
                'code' => 'PERCENTAGE',
                'description' => '3% percentage tax on gross sales/receipts',
                'rate' => 3.00,
                'sort_order' => 3,
            ],
            [
                'name' => 'Excise Tax',
                'code' => 'EXCISE',
                'description' => 'Tax on specific goods',
                'rate' => null, // Variable rate
                'sort_order' => 4,
            ],
        ];

        foreach ($types as $type) {
            DB::table('business_tax_types')->insert([
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
