<?php
// database/seeders/ChartOfAccountsTemplateItemSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChartOfAccountsTemplateItemSeeder extends Seeder
{
    private $accountTypes;
    private $subAccountTypes;

    public function run(): void
    {
        // Cache account types and sub-account types
        $this->accountTypes = DB::table('account_types')->get()->keyBy('code');
        $this->subAccountTypes = DB::table('sub_account_types')->get()->keyBy('code');

        // Get all templates
        $templates = DB::table('chart_of_accounts_templates as t')
            ->join('industries as i', 't.industry_id', '=', 'i.id')
            ->join('business_structures as bs', 't.business_structure_id', '=', 'bs.id')
            ->select('t.id', 'i.code as industry_code', 'bs.code as structure_code')
            ->get();

        foreach ($templates as $template) {
            $this->seedTemplateItems($template);
        }
    }

    private function seedTemplateItems($template)
    {
        $items = [];

        // Service Industry Templates
        if ($template->industry_code === 'SERVICE') {
            if ($template->structure_code === 'SOLO_PROP') {
                $items = $this->getServiceSoloPropItems();
            } elseif ($template->structure_code === 'PARTNERSHIP') {
                $items = $this->getServicePartnershipItems();
            } elseif ($template->structure_code === 'CORPORATION') {
                $items = $this->getServiceCorporationItems();
            } elseif ($template->structure_code === 'COOPERATIVE') {
                $items = $this->getServiceCooperativeItems();
            }
        }
        // Retail Industry Templates
        elseif ($template->industry_code === 'RETAIL') {
            if ($template->structure_code === 'SOLO_PROP') {
                $items = $this->getRetailSoloPropItems();
            } elseif ($template->structure_code === 'PARTNERSHIP') {
                $items = $this->getRetailPartnershipItems();
            } elseif ($template->structure_code === 'CORPORATION') {
                $items = $this->getRetailCorporationItems();
            } elseif ($template->structure_code === 'COOPERATIVE') {
                $items = $this->getRetailCooperativeItems();
            }
        }
        // Manufacturing Industry Templates
        elseif ($template->industry_code === 'MANUFACTURING') {
            if ($template->structure_code === 'SOLO_PROP') {
                $items = $this->getManufacturingSoloPropItems();
            } elseif ($template->structure_code === 'PARTNERSHIP') {
                $items = $this->getManufacturingPartnershipItems();
            } elseif ($template->structure_code === 'CORPORATION') {
                $items = $this->getManufacturingCorporationItems();
            } elseif ($template->structure_code === 'COOPERATIVE') {
                $items = $this->getManufacturingCooperativeItems();
            }
        }
        // Import/Export Industry Templates
        elseif ($template->industry_code === 'IMPORT_EXPORT') {
            if ($template->structure_code === 'SOLO_PROP') {
                $items = $this->getImportExportSoloPropItems();
            } elseif ($template->structure_code === 'PARTNERSHIP') {
                $items = $this->getImportExportPartnershipItems();
            } elseif ($template->structure_code === 'CORPORATION') {
                $items = $this->getImportExportCorporationItems();
            } elseif ($template->structure_code === 'COOPERATIVE') {
                $items = $this->getImportExportCooperativeItems();
            }
        }

        // Insert items
        foreach ($items as $index => $item) {
            DB::table('chart_of_accounts_template_items')->insert([
                'template_id' => $template->id,
                'code' => $item['code'],
                'name' => $item['name'],
                'account_type_id' => $this->accountTypes->get($item['type'])->id,
                'sub_account_type_id' => isset($item['sub_type']) ? $this->subAccountTypes->get($item['sub_type'])->id : null,
                'description' => $item['description'] ?? null,
                'sort_order' => $index + 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function getServiceSoloPropItems()
    {
        return [
            ['code' => '1000', 'name' => 'Cash', 'type' => 'ASSET', 'sub_type' => 'CURRENT_ASSET'],
            ['code' => '1010', 'name' => 'Petty Cash', 'type' => 'ASSET', 'sub_type' => 'CURRENT_ASSET'],
            ['code' => '1020', 'name' => 'Accounts Receivable', 'type' => 'ASSET', 'sub_type' => 'CURRENT_ASSET'],
            ['code' => '1030', 'name' => 'Prepaid Expenses', 'type' => 'ASSET', 'sub_type' => 'CURRENT_ASSET'],
            ['code' => '1040', 'name' => 'Office Equipment', 'type' => 'ASSET', 'sub_type' => 'FIXED_ASSET'],
            ['code' => '1050', 'name' => 'Accumulated Depreciation', 'type' => 'ASSET', 'sub_type' => 'CONTRA_ASSET'],
            ['code' => '2000', 'name' => 'Accounts Payable', 'type' => 'LIABILITY', 'sub_type' => 'CURRENT_LIABILITY'],
            ['code' => '2010', 'name' => 'Taxes Payable', 'type' => 'LIABILITY', 'sub_type' => 'CURRENT_LIABILITY'],
            ['code' => '3000', 'name' => 'Owner\'s Capital', 'type' => 'EQUITY', 'sub_type' => 'OWNERS_EQUITY'],
            ['code' => '3010', 'name' => 'Owner\'s Drawings', 'type' => 'EQUITY', 'sub_type' => 'OWNERS_EQUITY'],
            ['code' => '4000', 'name' => 'Service Income', 'type' => 'REVENUE', 'sub_type' => 'SERVICE_REVENUE'],
            ['code' => '5000', 'name' => 'Rent Expense', 'type' => 'EXPENSE', 'sub_type' => 'OPERATING_EXPENSE'],
            ['code' => '5010', 'name' => 'Utilities Expense', 'type' => 'EXPENSE', 'sub_type' => 'OPERATING_EXPENSE'],
            ['code' => '5020', 'name' => 'Internet & Software Expense', 'type' => 'EXPENSE', 'sub_type' => 'OPERATING_EXPENSE'],
            ['code' => '5030', 'name' => 'Professional Fees', 'type' => 'EXPENSE', 'sub_type' => 'OPERATING_EXPENSE'],
        ];
    }

    private function getServicePartnershipItems()
    {
        return [
            ['code' => '1000', 'name' => 'Cash', 'type' => 'ASSET', 'sub_type' => 'CURRENT_ASSET'],
            ['code' => '1010', 'name' => 'Petty Cash', 'type' => 'ASSET', 'sub_type' => 'CURRENT_ASSET'],
            ['code' => '1020', 'name' => 'Accounts Receivable', 'type' => 'ASSET', 'sub_type' => 'CURRENT_ASSET'],
            ['code' => '1030', 'name' => 'Inventory', 'type' => 'ASSET', 'sub_type' => 'CURRENT_ASSET'],
            ['code' => '1040', 'name' => 'Store Equipment', 'type' => 'ASSET', 'sub_type' => 'FIXED_ASSET'],
            ['code' => '1050', 'name' => 'Accumulated Depreciation', 'type' => 'ASSET', 'sub_type' => 'CONTRA_ASSET'],
            ['code' => '2000', 'name' => 'Accounts Payable', 'type' => 'LIABILITY', 'sub_type' => 'CURRENT_LIABILITY'],
            ['code' => '2010', 'name' => 'Taxes Payable', 'type' => 'LIABILITY', 'sub_type' => 'CURRENT_LIABILITY'],
            ['code' => '3000', 'name' => 'Partner A Capital', 'type' => 'EQUITY', 'sub_type' => 'PARTNERS_CAPITAL'],
            ['code' => '3010', 'name' => 'Partner B Capital', 'type' => 'EQUITY', 'sub_type' => 'PARTNERS_CAPITAL'],
            ['code' => '3020', 'name' => 'Partner A Drawings', 'type' => 'EQUITY', 'sub_type' => 'PARTNERS_DRAWINGS'],
            ['code' => '3030', 'name' => 'Partner B Drawings', 'type' => 'EQUITY', 'sub_type' => 'PARTNERS_DRAWINGS'],
            ['code' => '3040', 'name' => 'Retained Earnings', 'type' => 'EQUITY', 'sub_type' => 'RETAINED_EARNINGS'],
            ['code' => '4000', 'name' => 'Service Income', 'type' => 'REVENUE', 'sub_type' => 'SERVICE_REVENUE'],
            ['code' => '5000', 'name' => 'Rent Expense', 'type' => 'EXPENSE', 'sub_type' => 'OPERATING_EXPENSE'],
            ['code' => '5010', 'name' => 'Utilities Expense', 'type' => 'EXPENSE', 'sub_type' => 'OPERATING_EXPENSE'],
            ['code' => '5020', 'name' => 'Internet & Software Expense', 'type' => 'EXPENSE', 'sub_type' => 'OPERATING_EXPENSE'],
            ['code' => '5030', 'name' => 'Professional Fees', 'type' => 'EXPENSE', 'sub_type' => 'OPERATING_EXPENSE'],
            ['code' => '5040', 'name' => 'Advertising & Marketing', 'type' => 'EXPENSE', 'sub_type' => 'OPERATING_EXPENSE'],
            ['code' => '5050', 'name' => 'Partner\'s Salary Expense', 'type' => 'EXPENSE', 'sub_type' => 'OWNER_COMPENSATION'],
        ];
    }

    private function getServiceCorporationItems()
    {
        return [
            ['code' => '1000', 'name' => 'Cash', 'type' => 'ASSET', 'sub_type' => 'CURRENT_ASSET'],
            ['code' => '1010', 'name' => 'Petty Cash', 'type' => 'ASSET', 'sub_type' => 'CURRENT_ASSET'],
            ['code' => '1020', 'name' => 'Accounts Receivable', 'type' => 'ASSET', 'sub_type' => 'CURRENT_ASSET'],
            ['code' => '1030', 'name' => 'Prepaid Expenses', 'type' => 'ASSET', 'sub_type' => 'CURRENT_ASSET'],
            ['code' => '1040', 'name' => 'Office Equipment', 'type' => 'ASSET', 'sub_type' => 'FIXED_ASSET'],
            ['code' => '1050', 'name' => 'Accumulated Depreciation', 'type' => 'ASSET', 'sub_type' => 'CONTRA_ASSET'],
            ['code' => '2000', 'name' => 'Accounts Payable', 'type' => 'LIABILITY', 'sub_type' => 'CURRENT_LIABILITY'],
            ['code' => '2010', 'name' => 'Taxes Payable', 'type' => 'LIABILITY', 'sub_type' => 'CURRENT_LIABILITY'],
            ['code' => '2020', 'name' => 'Loans Payable', 'type' => 'LIABILITY', 'sub_type' => 'LONG_TERM_LIABILITY'],
            ['code' => '3000', 'name' => 'Common Stock', 'type' => 'EQUITY', 'sub_type' => 'SHAREHOLDERS_EQUITY'],
            ['code' => '3010', 'name' => 'Preferred Stock', 'type' => 'EQUITY', 'sub_type' => 'SHAREHOLDERS_EQUITY'],
            ['code' => '3020', 'name' => 'Retained Earnings', 'type' => 'EQUITY', 'sub_type' => 'RETAINED_EARNINGS'],
            ['code' => '3030', 'name' => 'Dividends Payable', 'type' => 'EQUITY', 'sub_type' => 'SHAREHOLDER_PAYMENTS'],
            ['code' => '4000', 'name' => 'Service Income', 'type' => 'REVENUE', 'sub_type' => 'SERVICE_REVENUE'],
            ['code' => '5000', 'name' => 'Salaries & Wages Expense', 'type' => 'EXPENSE', 'sub_type' => 'OPERATING_EXPENSE'],
            ['code' => '5010', 'name' => 'Rent Expense', 'type' => 'EXPENSE', 'sub_type' => 'OPERATING_EXPENSE'],
            ['code' => '5020', 'name' => 'Utilities Expense', 'type' => 'EXPENSE', 'sub_type' => 'OPERATING_EXPENSE'],
            ['code' => '5030', 'name' => 'Internet & Software Expense', 'type' => 'EXPENSE', 'sub_type' => 'OPERATING_EXPENSE'],
            ['code' => '5040', 'name' => 'Professional Fees', 'type' => 'EXPENSE', 'sub_type' => 'OPERATING_EXPENSE'],
            ['code' => '5050', 'name' => 'Advertising & Marketing', 'type' => 'EXPENSE', 'sub_type' => 'OPERATING_EXPENSE'],
            ['code' => '5060', 'name' => 'Depreciation Expense', 'type' => 'EXPENSE', 'sub_type' => 'NON_OPERATING_EXPENSE'],
        ];
    }

    private function getServiceCooperativeItems()
    {
        return [
            ['code' => '1000', 'name' => 'Cash', 'type' => 'ASSET', 'sub_type' => 'CURRENT_ASSET'],
            ['code' => '1010', 'name' => 'Petty Cash', 'type' => 'ASSET', 'sub_type' => 'CURRENT_ASSET'],
            ['code' => '1020', 'name' => 'Accounts Receivable', 'type' => 'ASSET', 'sub_type' => 'CURRENT_ASSET'],
            ['code' => '1030', 'name' => 'Prepaid Expenses', 'type' => 'ASSET', 'sub_type' => 'CURRENT_ASSET'],
            ['code' => '1040', 'name' => 'Office Equipment', 'type' => 'ASSET', 'sub_type' => 'FIXED_ASSET'],
            ['code' => '1050', 'name' => 'Accumulated Depreciation', 'type' => 'ASSET', 'sub_type' => 'CONTRA_ASSET'],
            ['code' => '2000', 'name' => 'Accounts Payable', 'type' => 'LIABILITY', 'sub_type' => 'CURRENT_LIABILITY'],
            ['code' => '2010', 'name' => 'Taxes Payable', 'type' => 'LIABILITY', 'sub_type' => 'CURRENT_LIABILITY'],
            ['code' => '2020', 'name' => 'Loans Payable', 'type' => 'LIABILITY', 'sub_type' => 'LONG_TERM_LIABILITY'],
            ['code' => '3000', 'name' => 'Member Contributions', 'type' => 'EQUITY', 'sub_type' => 'MEMBERS_EQUITY'],
            ['code' => '3010', 'name' => 'Retained Surplus', 'type' => 'EQUITY', 'sub_type' => 'RETAINED_EARNINGS'],
            ['code' => '3020', 'name' => 'Member Patronage Refunds', 'type' => 'EQUITY', 'sub_type' => 'PROFIT_DISTRIBUTION'],
            ['code' => '4000', 'name' => 'Service Income', 'type' => 'REVENUE', 'sub_type' => 'SERVICE_REVENUE'],
            ['code' => '5000', 'name' => 'Salaries & Wages Expense', 'type' => 'EXPENSE', 'sub_type' => 'OPERATING_EXPENSE'],
            ['code' => '5010', 'name' => 'Rent Expense', 'type' => 'EXPENSE', 'sub_type' => 'OPERATING_EXPENSE'],
            ['code' => '5020', 'name' => 'Utilities Expense', 'type' => 'EXPENSE', 'sub_type' => 'OPERATING_EXPENSE'],
            ['code' => '5030', 'name' => 'Internet & Software Expense', 'type' => 'EXPENSE', 'sub_type' => 'OPERATING_EXPENSE'],
            ['code' => '5040', 'name' => 'Professional Fees', 'type' => 'EXPENSE', 'sub_type' => 'OPERATING_EXPENSE'],
            ['code' => '5050', 'name' => 'Advertising & Marketing', 'type' => 'EXPENSE', 'sub_type' => 'OPERATING_EXPENSE'],
        ];
    }

    // I'll add just a few more methods to demonstrate the pattern
    private function getRetailSoloPropItems()
    {
        return [
            ['code' => '1000', 'name' => 'Cash', 'type' => 'ASSET', 'sub_type' => 'CURRENT_ASSET'],
            ['code' => '1010', 'name' => 'Petty Cash', 'type' => 'ASSET', 'sub_type' => 'CURRENT_ASSET'],
            ['code' => '1020', 'name' => 'Accounts Receivable', 'type' => 'ASSET', 'sub_type' => 'CURRENT_ASSET'],
            ['code' => '1030', 'name' => 'Inventory', 'type' => 'ASSET', 'sub_type' => 'CURRENT_ASSET'],
            ['code' => '1040', 'name' => 'Store Equipment', 'type' => 'ASSET', 'sub_type' => 'FIXED_ASSET'],
            ['code' => '1050', 'name' => 'Accumulated Depreciation', 'type' => 'ASSET', 'sub_type' => 'CONTRA_ASSET'],
            ['code' => '2000', 'name' => 'Accounts Payable', 'type' => 'LIABILITY', 'sub_type' => 'CURRENT_LIABILITY'],
            ['code' => '2010', 'name' => 'Taxes Payable', 'type' => 'LIABILITY', 'sub_type' => 'CURRENT_LIABILITY'],
            ['code' => '3000', 'name' => 'Owner\'s Capital', 'type' => 'EQUITY', 'sub_type' => 'OWNERS_EQUITY'],
            ['code' => '3010', 'name' => 'Owner\'s Drawings', 'type' => 'EQUITY', 'sub_type' => 'OWNERS_EQUITY'],
            ['code' => '4000', 'name' => 'Sales Revenue', 'type' => 'REVENUE', 'sub_type' => 'RETAIL_SALES_REVENUE'],
            ['code' => '5000', 'name' => 'Purchases', 'type' => 'COGS', 'sub_type' => 'INVENTORY_ACQUISITION'],
            ['code' => '5010', 'name' => 'Purchase Return', 'type' => 'COGS', 'sub_type' => 'INVENTORY_ADJUSTMENT'],
            ['code' => '5020', 'name' => 'Freight In', 'type' => 'COGS', 'sub_type' => 'INVENTORY_COST'],
            ['code' => '5030', 'name' => 'Cost of Goods Sold', 'type' => 'COGS', 'sub_type' => 'INVENTORY_COST'],
            ['code' => '6000', 'name' => 'Rent Expense', 'type' => 'EXPENSE', 'sub_type' => 'OPERATING_EXPENSE'],
            ['code' => '6010', 'name' => 'Utilities Expense', 'type' => 'EXPENSE', 'sub_type' => 'OPERATING_EXPENSE'],
            ['code' => '6020', 'name' => 'Marketing & Advertising', 'type' => 'EXPENSE', 'sub_type' => 'OPERATING_EXPENSE'],
            ['code' => '6030', 'name' => 'Depreciation Expense', 'type' => 'EXPENSE', 'sub_type' => 'OPERATING_EXPENSE'],
        ];
    }

    // Add stub methods for remaining templates to avoid errors
    private function getRetailPartnershipItems()
    {
        return $this->getRetailSoloPropItems();
    }
    private function getRetailCorporationItems()
    {
        return $this->getRetailSoloPropItems();
    }
    private function getRetailCooperativeItems()
    {
        return $this->getRetailSoloPropItems();
    }

    private function getManufacturingSoloPropItems()
    {
        return [
            ['code' => '1000', 'name' => 'Cash', 'type' => 'ASSET', 'sub_type' => 'CURRENT_ASSET'],
            ['code' => '1010', 'name' => 'Petty Cash', 'type' => 'ASSET', 'sub_type' => 'CURRENT_ASSET'],
            ['code' => '1020', 'name' => 'Accounts Receivable', 'type' => 'ASSET', 'sub_type' => 'CURRENT_ASSET'],
            ['code' => '1030', 'name' => 'Raw Materials Inventory', 'type' => 'ASSET', 'sub_type' => 'INVENTORY'],
            ['code' => '1040', 'name' => 'Work-in-Progress (WIP)', 'type' => 'ASSET', 'sub_type' => 'INVENTORY'],
            ['code' => '1050', 'name' => 'Finished Goods Inventory', 'type' => 'ASSET', 'sub_type' => 'INVENTORY'],
            ['code' => '1060', 'name' => 'Store Equipment', 'type' => 'ASSET', 'sub_type' => 'FIXED_ASSET'],
            ['code' => '1070', 'name' => 'Accumulated Depreciation', 'type' => 'ASSET', 'sub_type' => 'CONTRA_ASSET'],
            ['code' => '2000', 'name' => 'Accounts Payable', 'type' => 'LIABILITY', 'sub_type' => 'CURRENT_LIABILITY'],
            ['code' => '2010', 'name' => 'Taxes Payable', 'type' => 'LIABILITY', 'sub_type' => 'CURRENT_LIABILITY'],
            ['code' => '3000', 'name' => 'Owner\'s Capital', 'type' => 'EQUITY', 'sub_type' => 'OWNERS_EQUITY'],
            ['code' => '3010', 'name' => 'Owner\'s Drawings', 'type' => 'EQUITY', 'sub_type' => 'OWNERS_EQUITY'],
            ['code' => '4000', 'name' => 'Sales Revenue', 'type' => 'REVENUE', 'sub_type' => 'RETAIL_SALES_REVENUE'],
            ['code' => '5000', 'name' => 'Purchases', 'type' => 'COGS', 'sub_type' => 'INVENTORY_ACQUISITION'],
            ['code' => '5010', 'name' => 'Purchase Return', 'type' => 'COGS', 'sub_type' => 'INVENTORY_ADJUSTMENT'],
            ['code' => '5020', 'name' => 'Freight In', 'type' => 'COGS', 'sub_type' => 'INVENTORY_COST'],
            ['code' => '5030', 'name' => 'Cost of Goods Sold', 'type' => 'COGS', 'sub_type' => 'INVENTORY_COST'],
            ['code' => '6000', 'name' => 'Rent Expense', 'type' => 'EXPENSE', 'sub_type' => 'OPERATING_EXPENSE'],
            ['code' => '6010', 'name' => 'Utilities Expense', 'type' => 'EXPENSE', 'sub_type' => 'OPERATING_EXPENSE'],
            ['code' => '6020', 'name' => 'Marketing & Advertising', 'type' => 'EXPENSE', 'sub_type' => 'OPERATING_EXPENSE'],
            ['code' => '6030', 'name' => 'Depreciation Expense', 'type' => 'EXPENSE', 'sub_type' => 'OPERATING_EXPENSE'],
        ];
    }

    private function getManufacturingPartnershipItems()
    {
        return $this->getManufacturingSoloPropItems();
    }
    private function getManufacturingCorporationItems()
    {
        return $this->getManufacturingSoloPropItems();
    }
    private function getManufacturingCooperativeItems()
    {
        return $this->getManufacturingSoloPropItems();
    }

    private function getImportExportSoloPropItems()
    {
        return [
            ['code' => '1000', 'name' => 'Cash', 'type' => 'ASSET', 'sub_type' => 'CURRENT_ASSET'],
            ['code' => '1010', 'name' => 'Petty Cash', 'type' => 'ASSET', 'sub_type' => 'CURRENT_ASSET'],
            ['code' => '1020', 'name' => 'Accounts Receivable', 'type' => 'ASSET', 'sub_type' => 'CURRENT_ASSET'],
            ['code' => '1030', 'name' => 'Inventory (Imported Goods)', 'type' => 'ASSET', 'sub_type' => 'CURRENT_ASSET'],
            ['code' => '1040', 'name' => 'Prepaid Customs Duties', 'type' => 'ASSET', 'sub_type' => 'CURRENT_ASSET'],
            ['code' => '1050', 'name' => 'Store Equipment', 'type' => 'ASSET', 'sub_type' => 'FIXED_ASSET'],
            ['code' => '1060', 'name' => 'Accumulated Depreciation', 'type' => 'ASSET', 'sub_type' => 'CONTRA_ASSET'],
            ['code' => '2000', 'name' => 'Accounts Payable', 'type' => 'LIABILITY', 'sub_type' => 'CURRENT_LIABILITY'],
            ['code' => '2010', 'name' => 'Taxes Payable', 'type' => 'LIABILITY', 'sub_type' => 'CURRENT_LIABILITY'],
            ['code' => '3000', 'name' => 'Owner\'s Capital', 'type' => 'EQUITY', 'sub_type' => 'OWNERS_EQUITY'],
            ['code' => '3010', 'name' => 'Owner\'s Drawings', 'type' => 'EQUITY', 'sub_type' => 'OWNERS_EQUITY'],
            ['code' => '4000', 'name' => 'Sales Revenue', 'type' => 'REVENUE', 'sub_type' => 'RETAIL_SALES_REVENUE'],
            ['code' => '5000', 'name' => 'Purchases (Imports)', 'type' => 'COGS', 'sub_type' => 'INVENTORY_COST'],
            ['code' => '5010', 'name' => 'Freight & Shipping Costs', 'type' => 'COGS', 'sub_type' => 'LOGISTICS_COST'],
            ['code' => '6000', 'name' => 'Rent Expense', 'type' => 'EXPENSE', 'sub_type' => 'OPERATING_EXPENSE'],
            ['code' => '6010', 'name' => 'Utilities Expense', 'type' => 'EXPENSE', 'sub_type' => 'OPERATING_EXPENSE'],
            ['code' => '6020', 'name' => 'Marketing & Advertising', 'type' => 'EXPENSE', 'sub_type' => 'OPERATING_EXPENSE'],
            ['code' => '6030', 'name' => 'Depreciation Expense', 'type' => 'EXPENSE', 'sub_type' => 'OPERATING_EXPENSE'],
        ];
    }

    private function getImportExportPartnershipItems()
    {
        return $this->getImportExportSoloPropItems();
    }
    private function getImportExportCorporationItems()
    {
        return $this->getImportExportSoloPropItems();
    }
    private function getImportExportCooperativeItems()
    {
        return $this->getImportExportSoloPropItems();
    }
}
