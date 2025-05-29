<?php
// database/seeders/RegistrationTypeSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegistrationTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'BIR Registration',
                'code' => 'BIR',
                'description' => 'Bureau of Internal Revenue registration',
                'requires_expiry' => false,
                'requires_document' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'DTI Registration',
                'code' => 'DTI',
                'description' => 'Department of Trade and Industry registration',
                'requires_expiry' => true,
                'requires_document' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'SEC Registration',
                'code' => 'SEC',
                'description' => 'Securities and Exchange Commission registration',
                'requires_expiry' => false,
                'requires_document' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'LGU Registration',
                'code' => 'LGU',
                'description' => 'Local Government Unit business permit',
                'requires_expiry' => true,
                'requires_document' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'CDA Registration',
                'code' => 'CDA',
                'description' => 'Cooperative Development Authority registration',
                'requires_expiry' => false,
                'requires_document' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($types as $type) {
            DB::table('registration_types')->insert([
                'name' => $type['name'],
                'code' => $type['code'],
                'description' => $type['description'],
                'requires_expiry' => $type['requires_expiry'],
                'requires_document' => $type['requires_document'],
                'sort_order' => $type['sort_order'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
