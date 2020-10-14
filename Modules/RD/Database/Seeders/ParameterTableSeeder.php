<?php

namespace Modules\RD\Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Parameter;

class ParameterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        Parameter::updateOrCreate([
            'name'  => 'recipe_sample_size',
            'value' => '100',
            'order' => 1,
            'description' => 'Recipe Sample Size for making calculations',
            'is_internal' => true,
            'is_default' => false,
        ]);

        Parameter::updateOrCreate([
            'name'  => 'recipe_type',
            'value' => 'FK',
            'order' => 1,
            'description' => 'Key',
            'is_internal' => true,
            'is_default' => false,
        ]);

        Parameter::updateOrCreate([
            'name'  => 'recipe_type',
            'value' => 'FL',
            'order' => 2,
            'description' => 'Flavor',
            'is_internal' => true,
            'is_default' => false,
        ]);

        Parameter::updateOrCreate([
            'name'  => 'recipe_type',
            'value' => 'SL',
            'order' => 3,
            'description' => 'Solution',
            'is_internal' => true,
            'is_default' => false,
        ]);

    }
}
