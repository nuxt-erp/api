<?php

namespace Modules\Inventory\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Inventory\Entities\BinImportSettings;

class ImportBinSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        BinImportSettings::firstOrCreate(
            ['column_name' => 'location_id', 'entity' => 'bin']
        );
        BinImportSettings::firstOrCreate(
            ['column_name' => 'name', 'entity' => 'bin']
        );
        BinImportSettings::firstOrCreate(
            ['column_name' => 'is_enabled', 'entity' => 'bin']
        );
        BinImportSettings::firstOrCreate(
            ['column_name' => 'barcode', 'entity' => 'bin']
        );
    
    }
}
