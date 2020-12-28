<?php

namespace Modules\Inventory\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class InventoryDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(ParameterSeederTableSeeder::class);
        $this->call(AttributeTableSeeder::class);
        $this->call(ProvinceTaxSeeder::class);
        // $this->call(ImportProductSettingsSeeder::class);
        $this->call(ImportBinSettingsSeeder::class);
    }
}
