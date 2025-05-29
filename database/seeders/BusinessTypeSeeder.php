<?php
// database/seeders/BusinessTypeSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'Services',
                'code' => 'SERVICES',
                'description' => 'Business providing professional or technical services',
                'sort_order' => 1,
            ],
            [
                'name' => 'Retail',
                'code' => 'RETAIL',
                'description' => 'Business selling goods directly to consumers',
                'sort_order' => 2,
            ],
            [
                'name' => 'Manufacturing',
                'code' => 'MANUFACTURING',
                'description' => 'Business producing goods from raw materials',
                'sort_order' => 3,
            ],
            [
                'name' => 'Import/Export',
                'code' => 'IMPORT_EXPORT',
                'description' => 'Business involved in international trade',
                'sort_order' => 4,
            ],
        ];

        foreach ($types as $type) {
            DB::table('business_types')->insert([
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
