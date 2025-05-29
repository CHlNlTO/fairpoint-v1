<?php
// database/seeders/IndustrySeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndustrySeeder extends Seeder
{
    public function run(): void
    {
        $industries = [
            [
                'name' => 'Service Industry',
                'code' => 'SERVICE',
                'description' => 'Industries providing intangible goods or services',
                'sort_order' => 1,
            ],
            [
                'name' => 'Retail Industry',
                'code' => 'RETAIL',
                'description' => 'Industries selling consumer goods',
                'sort_order' => 2,
            ],
            [
                'name' => 'Manufacturing Industry',
                'code' => 'MANUFACTURING',
                'description' => 'Industries producing goods from raw materials',
                'sort_order' => 3,
            ],
            [
                'name' => 'Import/Export Industry',
                'code' => 'IMPORT_EXPORT',
                'description' => 'Industries involved in international trade',
                'sort_order' => 4,
            ],
        ];

        foreach ($industries as $industry) {
            DB::table('industries')->insert([
                'name' => $industry['name'],
                'code' => $industry['code'],
                'description' => $industry['description'],
                'sort_order' => $industry['sort_order'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
