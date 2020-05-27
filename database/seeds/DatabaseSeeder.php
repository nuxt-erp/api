<?php

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
        //$this->call(RolesTableSeeder::class);
        $this->call(CountriesSeeder::class);
        $this->call(ProvincesSeeder::class);
        $this->call(CompanySeeder::class);
        //$this->call(UsersTableSeeder::class);
        $this->call(AttributeTableSeeder::class);
        $this->call(SystemParameterTableSeeder::class);
    }
}
