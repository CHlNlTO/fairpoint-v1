<?php
// database/seeders/BusinessRegistrationSeeder.php

namespace Database\Seeders;

use Database\Seeders\Vendor\Yajra\AddressSeeder;
use Illuminate\Database\Seeder;

class BusinessRegistrationSeeder extends Seeder
{
    public function run(): void
    {
        // Call seeders in the correct order to handle foreign key dependencies

        // 1. Base reference tables (no dependencies)
        $this->call(AddressSeeder::class);
        $this->call(FilamentColorSeeder::class);
        $this->call(BusinessTypeSeeder::class);
        $this->call(BusinessStructureSeeder::class);
        $this->call(IndustrySeeder::class);
        $this->call(RegistrationTypeSeeder::class);
        $this->call(IncomeTaxTypeSeeder::class);
        $this->call(BusinessTaxTypeSeeder::class);

        // 2. Status tables (depends on colors)
        $this->call(BusinessStatusSeeder::class);
        $this->call(BusinessUserStatusSeeder::class);

        // 3. Account structure tables
        $this->call(AccountTypeSeeder::class);
        $this->call(SubAccountTypeSeeder::class);

        // 4. Chart of Accounts templates (depends on industries and structures)
        $this->call(ChartOfAccountsTemplateSeeder::class);
        $this->call(ChartOfAccountsTemplateItemSeeder::class);

        // Note: Address seeders (provinces, cities, barangays) are not included
        // as they require external data source or package
    }
}
