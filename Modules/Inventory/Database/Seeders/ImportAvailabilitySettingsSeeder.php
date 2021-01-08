<?php

namespace Modules\Inventory\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Inventory\Entities\AvailabilityImportSettings;

class ImportAvailabilitySettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        AvailabilityImportSettings::firstOrCreate(
            ['column_name' => 'sku', 'entity' => 'product']
        );
        AvailabilityImportSettings::firstOrCreate(
            ['column_name' => 'name', 'entity' => 'product']
        );
        AvailabilityImportSettings::firstOrCreate(
            ['column_name' => 'quantity', 'entity' => 'availability']
        );
        AvailabilityImportSettings::firstOrCreate(
            ['column_name' => 'barcode', 'entity' => 'bin']
        );
        AvailabilityImportSettings::firstOrCreate(
            ['column_name' => 'name', 'entity' => 'location']
        );

    }
}
