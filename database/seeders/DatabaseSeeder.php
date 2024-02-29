<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // master
        $this->call(CountriesTableSeeder::class);
        $this->call(BusinessTypeTableSeeder::class);
        $this->call(GroupsTableSeeder::class);
        $this->call(CompaniesTableSeeder::class);
        $this->call(CompanyDetailsTableSeeder::class);
        $this->call(CompanyBankAccountTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(GlobalInfoTableSeeder::class);
        $this->call(CurrencySeeder::class);
        $this->call(SystemSettingTableSeeder::class);
        $this->call(CurrencyConversionTableSeeder::class);
    }
}
