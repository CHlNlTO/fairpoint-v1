<?php
// database/seeders/BusinessStructureSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessStructureSeeder extends Seeder
{
    public function run(): void
    {
        $structures = [
            [
                'name' => 'Freelancing / Solo Proprietorship',
                'code' => 'SOLO_PROP',
                'description' => 'Business owned and operated by a single individual',
                'sort_order' => 1,
            ],
            [
                'name' => 'Partnership',
                'code' => 'PARTNERSHIP',
                'description' => 'Business owned by two or more partners',
                'sort_order' => 2,
            ],
            [
                'name' => 'Corporation',
                'code' => 'CORPORATION',
                'description' => 'Business entity separate from its owners with shares of stock',
                'sort_order' => 3,
            ],
            [
                'name' => 'Cooperative',
                'code' => 'COOPERATIVE',
                'description' => 'Business owned and operated by members for mutual benefit',
                'sort_order' => 4,
            ],
        ];

        foreach ($structures as $structure) {
            DB::table('business_structures')->insert([
                'name' => $structure['name'],
                'code' => $structure['code'],
                'description' => $structure['description'],
                'sort_order' => $structure['sort_order'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
