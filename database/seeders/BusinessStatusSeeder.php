<?php
// database/seeders/BusinessStatusSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessStatusSeeder extends Seeder
{
    public function run(): void
    {
        // Get color IDs
        $grayColor = DB::table('filament_colors')->where('name', 'gray')->first();
        $yellowColor = DB::table('filament_colors')->where('name', 'yellow')->first();
        $successColor = DB::table('filament_colors')->where('name', 'success')->first();
        $dangerColor = DB::table('filament_colors')->where('name', 'danger')->first();
        $warningColor = DB::table('filament_colors')->where('name', 'warning')->first();

        $statuses = [
            [
                'name' => 'Draft',
                'code' => 'DRAFT',
                'color_id' => $grayColor->id ?? null,
                'description' => 'Business registration not yet completed',
                'sort_order' => 1,
            ],
            [
                'name' => 'In Progress',
                'code' => 'IN_PROGRESS',
                'color_id' => $yellowColor->id ?? null,
                'description' => 'Business registration in progress',
                'sort_order' => 2,
            ],
            [
                'name' => 'Active',
                'code' => 'ACTIVE',
                'color_id' => $successColor->id ?? null,
                'description' => 'Business is active and operational',
                'sort_order' => 3,
            ],
            [
                'name' => 'Inactive',
                'code' => 'INACTIVE',
                'color_id' => $dangerColor->id ?? null,
                'description' => 'Business is temporarily inactive',
                'sort_order' => 4,
            ],
            [
                'name' => 'Suspended',
                'code' => 'SUSPENDED',
                'color_id' => $warningColor->id ?? null,
                'description' => 'Business operations suspended',
                'sort_order' => 5,
            ],
        ];

        foreach ($statuses as $status) {
            DB::table('business_statuses')->insert([
                'name' => $status['name'],
                'code' => $status['code'],
                'color_id' => $status['color_id'],
                'description' => $status['description'],
                'sort_order' => $status['sort_order'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
