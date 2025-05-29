<?php
// database/seeders/BusinessUserStatusSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessUserStatusSeeder extends Seeder
{
    public function run(): void
    {
        // Get color IDs
        $successColor = DB::table('filament_colors')->where('name', 'success')->first();
        $warningColor = DB::table('filament_colors')->where('name', 'warning')->first();
        $dangerColor = DB::table('filament_colors')->where('name', 'danger')->first();
        $grayColor = DB::table('filament_colors')->where('name', 'gray')->first();

        $statuses = [
            [
                'name' => 'Active',
                'code' => 'ACTIVE',
                'color_id' => $successColor->id ?? null,
                'description' => 'User has active access to the business',
                'sort_order' => 1,
            ],
            [
                'name' => 'Pending',
                'code' => 'PENDING',
                'color_id' => $warningColor->id ?? null,
                'description' => 'User invitation pending acceptance',
                'sort_order' => 2,
            ],
            [
                'name' => 'Suspended',
                'code' => 'SUSPENDED',
                'color_id' => $dangerColor->id ?? null,
                'description' => 'User access temporarily suspended',
                'sort_order' => 3,
            ],
            [
                'name' => 'Revoked',
                'code' => 'REVOKED',
                'color_id' => $grayColor->id ?? null,
                'description' => 'User access permanently revoked',
                'sort_order' => 4,
            ],
        ];

        foreach ($statuses as $status) {
            DB::table('business_user_statuses')->insert([
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
