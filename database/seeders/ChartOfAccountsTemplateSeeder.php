<?php
// database/seeders/ChartOfAccountsTemplateSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChartOfAccountsTemplateSeeder extends Seeder
{
    public function run(): void
    {
        // Get IDs
        $serviceIndustry = DB::table('industries')->where('code', 'SERVICE')->first();
        $retailIndustry = DB::table('industries')->where('code', 'RETAIL')->first();
        $manufacturingIndustry = DB::table('industries')->where('code', 'MANUFACTURING')->first();
        $importExportIndustry = DB::table('industries')->where('code', 'IMPORT_EXPORT')->first();

        $soloProp = DB::table('business_structures')->where('code', 'SOLO_PROP')->first();
        $partnership = DB::table('business_structures')->where('code', 'PARTNERSHIP')->first();
        $corporation = DB::table('business_structures')->where('code', 'CORPORATION')->first();
        $cooperative = DB::table('business_structures')->where('code', 'COOPERATIVE')->first();

        $templates = [
            // Service Industry Templates
            ['name' => 'Service Industry - Freelancing/Solo Proprietorship', 'industry_id' => $serviceIndustry->id, 'business_structure_id' => $soloProp->id],
            ['name' => 'Service Industry - Partnership', 'industry_id' => $serviceIndustry->id, 'business_structure_id' => $partnership->id],
            ['name' => 'Service Industry - Corporation', 'industry_id' => $serviceIndustry->id, 'business_structure_id' => $corporation->id],
            ['name' => 'Service Industry - Cooperative', 'industry_id' => $serviceIndustry->id, 'business_structure_id' => $cooperative->id],

            // Retail Industry Templates
            ['name' => 'Retail Industry - Freelancing/Solo Proprietorship', 'industry_id' => $retailIndustry->id, 'business_structure_id' => $soloProp->id],
            ['name' => 'Retail Industry - Partnership', 'industry_id' => $retailIndustry->id, 'business_structure_id' => $partnership->id],
            ['name' => 'Retail Industry - Corporation', 'industry_id' => $retailIndustry->id, 'business_structure_id' => $corporation->id],
            ['name' => 'Retail Industry - Cooperative', 'industry_id' => $retailIndustry->id, 'business_structure_id' => $cooperative->id],

            // Manufacturing Industry Templates
            ['name' => 'Manufacturing Industry - Freelancing/Solo Proprietorship', 'industry_id' => $manufacturingIndustry->id, 'business_structure_id' => $soloProp->id],
            ['name' => 'Manufacturing Industry - Partnership', 'industry_id' => $manufacturingIndustry->id, 'business_structure_id' => $partnership->id],
            ['name' => 'Manufacturing Industry - Corporation', 'industry_id' => $manufacturingIndustry->id, 'business_structure_id' => $corporation->id],
            ['name' => 'Manufacturing Industry - Cooperative', 'industry_id' => $manufacturingIndustry->id, 'business_structure_id' => $cooperative->id],

            // Import/Export Industry Templates
            ['name' => 'Import/Export Industry - Freelancing/Solo Proprietorship', 'industry_id' => $importExportIndustry->id, 'business_structure_id' => $soloProp->id],
            ['name' => 'Import/Export Industry - Partnership', 'industry_id' => $importExportIndustry->id, 'business_structure_id' => $partnership->id],
            ['name' => 'Import/Export Industry - Corporation', 'industry_id' => $importExportIndustry->id, 'business_structure_id' => $corporation->id],
            ['name' => 'Import/Export Industry - Cooperative', 'industry_id' => $importExportIndustry->id, 'business_structure_id' => $cooperative->id],
        ];

        foreach ($templates as $template) {
            DB::table('chart_of_accounts_templates')->insert([
                'name' => $template['name'],
                'industry_id' => $template['industry_id'],
                'business_structure_id' => $template['business_structure_id'],
                'version' => '1.0',
                'description' => 'Initial template for ' . $template['name'],
                'is_active' => true,
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
