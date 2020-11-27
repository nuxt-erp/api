<?php

namespace Modules\RD\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\RD\Entities\RecipeImportSettings;

class ImportRecipeSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        RecipeImportSettings::firstOrCreate(
            ['column_name' => 'code', 'entity' => 'recipe']
        );
        RecipeImportSettings::firstOrCreate(
            ['column_name' => 'name', 'entity' => 'recipe']
        );
        RecipeImportSettings::firstOrCreate(
            ['column_name' => 'quantity', 'entity' => 'ingredients']
        );
        RecipeImportSettings::firstOrCreate(
            ['column_name' => 'product_sku', 'entity' => 'ingredients']
        );

    }
}
