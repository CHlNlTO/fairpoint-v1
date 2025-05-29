<?php
// database/seeders/SubAccountTypeSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubAccountTypeSeeder extends Seeder
{
    public function run(): void
    {
        // Get account type IDs
        $asset = DB::table('account_types')->where('code', 'ASSET')->first();
        $liability = DB::table('account_types')->where('code', 'LIABILITY')->first();
        $equity = DB::table('account_types')->where('code', 'EQUITY')->first();
        $revenue = DB::table('account_types')->where('code', 'REVENUE')->first();
        $expense = DB::table('account_types')->where('code', 'EXPENSE')->first();
        $cogs = DB::table('account_types')->where('code', 'COGS')->first();

        $subTypes = [
            // Asset sub-types
            ['name' => 'Current Asset', 'code' => 'CURRENT_ASSET', 'account_type_id' => $asset->id, 'sort_order' => 1],
            ['name' => 'Fixed Asset', 'code' => 'FIXED_ASSET', 'account_type_id' => $asset->id, 'sort_order' => 2],
            ['name' => 'Contra Asset', 'code' => 'CONTRA_ASSET', 'account_type_id' => $asset->id, 'sort_order' => 3],
            ['name' => 'Inventory', 'code' => 'INVENTORY', 'account_type_id' => $asset->id, 'sort_order' => 4],

            // Liability sub-types
            ['name' => 'Current Liability', 'code' => 'CURRENT_LIABILITY', 'account_type_id' => $liability->id, 'sort_order' => 1],
            ['name' => 'Long-Term Liability', 'code' => 'LONG_TERM_LIABILITY', 'account_type_id' => $liability->id, 'sort_order' => 2],

            // Equity sub-types
            ['name' => 'Owner\'s Equity', 'code' => 'OWNERS_EQUITY', 'account_type_id' => $equity->id, 'sort_order' => 1],
            ['name' => 'Partner\'s Capital', 'code' => 'PARTNERS_CAPITAL', 'account_type_id' => $equity->id, 'sort_order' => 1],
            ['name' => 'Partner\'s Drawings', 'code' => 'PARTNERS_DRAWINGS', 'account_type_id' => $equity->id, 'sort_order' => 2],
            ['name' => 'Shareholder\'s Equity', 'code' => 'SHAREHOLDERS_EQUITY', 'account_type_id' => $equity->id, 'sort_order' => 3],
            ['name' => 'Retained Earnings', 'code' => 'RETAINED_EARNINGS', 'account_type_id' => $equity->id, 'sort_order' => 4],
            ['name' => 'Member\'s Equity', 'code' => 'MEMBERS_EQUITY', 'account_type_id' => $equity->id, 'sort_order' => 5],
            ['name' => 'Profit Distribution', 'code' => 'PROFIT_DISTRIBUTION', 'account_type_id' => $equity->id, 'sort_order' => 6],
            ['name' => 'Shareholder Payments', 'code' => 'SHAREHOLDER_PAYMENTS', 'account_type_id' => $equity->id, 'sort_order' => 7],

            // Revenue sub-types
            ['name' => 'Service Revenue', 'code' => 'SERVICE_REVENUE', 'account_type_id' => $revenue->id, 'sort_order' => 1],
            ['name' => 'Retail Sales Revenue', 'code' => 'RETAIL_SALES_REVENUE', 'account_type_id' => $revenue->id, 'sort_order' => 2],

            // Expense sub-types
            ['name' => 'Operating Expense', 'code' => 'OPERATING_EXPENSE', 'account_type_id' => $expense->id, 'sort_order' => 1],
            ['name' => 'Non-Operating Expense', 'code' => 'NON_OPERATING_EXPENSE', 'account_type_id' => $expense->id, 'sort_order' => 2],
            ['name' => 'Owner Compensation', 'code' => 'OWNER_COMPENSATION', 'account_type_id' => $expense->id, 'sort_order' => 3],

            // COGS sub-types
            ['name' => 'Inventory Acquisition', 'code' => 'INVENTORY_ACQUISITION', 'account_type_id' => $cogs->id, 'sort_order' => 1],
            ['name' => 'Inventory Adjustment', 'code' => 'INVENTORY_ADJUSTMENT', 'account_type_id' => $cogs->id, 'sort_order' => 2],
            ['name' => 'Inventory Cost', 'code' => 'INVENTORY_COST', 'account_type_id' => $cogs->id, 'sort_order' => 3],
            ['name' => 'Logistics Cost', 'code' => 'LOGISTICS_COST', 'account_type_id' => $cogs->id, 'sort_order' => 4],
        ];

        foreach ($subTypes as $subType) {
            DB::table('sub_account_types')->insert([
                'name' => $subType['name'],
                'code' => $subType['code'],
                'account_type_id' => $subType['account_type_id'],
                'description' => null,
                'sort_order' => $subType['sort_order'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
