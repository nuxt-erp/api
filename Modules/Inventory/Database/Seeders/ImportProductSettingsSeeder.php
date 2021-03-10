<?php

namespace Modules\Inventory\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Inventory\Entities\Attribute;
use Modules\Inventory\Entities\ProductImportSettings;

class ImportProductSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        
        ProductImportSettings::firstOrCreate(
            ['column_name' => 'sku', 'entity' => 'product']
        );
        ProductImportSettings::firstOrCreate(
            ['column_name' => 'name', 'entity' => 'product']
        );
        ProductImportSettings::firstOrCreate(
            ['column_name' => 'name', 'entity' => 'category']
        );
        ProductImportSettings::firstOrCreate(
            ['column_name' => 'name', 'entity' => 'measure']
        );
        ProductImportSettings::firstOrCreate(
            ['column_name' => 'barcode', 'entity' => 'product']
        );
        ProductImportSettings::firstOrCreate(
            ['column_name' => 'description', 'entity' => 'product']
        );
        ProductImportSettings::firstOrCreate(
            ['column_name' => 'carton_barcode', 'entity' => 'product']
        );
        ProductImportSettings::firstOrCreate(
            ['column_name' => 'carton_qty', 'entity' => 'product']
        );
    }
}
