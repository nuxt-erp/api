<?php

namespace Modules\RD\Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Parameter;

class RecipeSpecificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        Parameter::updateOrCreate([
            'name'  => 'recipe_spec_attributes',
            'value' => 'isMilk',
            'order' => 1,
            'is_internal' => true,
            'is_default' => false
        ]);
        Parameter::updateOrCreate([
            'name'  => 'recipe_spec_attributes',
            'value' => 'isEgg',
            'order' => 2,
            'is_internal' => true,
            'is_default' => false
        ]);
        Parameter::updateOrCreate([
            'name'  => 'recipe_spec_attributes',
            'value' => 'isSoy',
            'order' => 3,
            'is_internal' => true,
            'is_default' => false
        ]);
        Parameter::updateOrCreate([
            'name'  => 'recipe_spec_attributes',
            'value' => 'isWheat',
            'order' => 4,
            'is_internal' => true,
            'is_default' => false
        ]);
        Parameter::updateOrCreate([
            'name'  => 'recipe_spec_attributes',
            'value' => 'isTreeNut',
            'order' => 5,
            'is_internal' => true,
            'is_default' => false
        ]);
        Parameter::updateOrCreate([
            'name'  => 'recipe_spec_attributes',
            'value' => 'isFish',
            'order' => 6,
            'is_internal' => true,
            'is_default' => false
        ]);
        Parameter::updateOrCreate([
            'name'  => 'recipe_spec_attributes',
            'value' => 'isPeanut',
            'order' => 7,
            'is_internal' => true,
            'is_default' => false
        ]);
        Parameter::updateOrCreate([
            'name'  => 'recipe_spec_attributes',
            'value' => 'isShellfish',
            'order' => 8,
            'is_internal' => true,
            'is_default' => false
        ]);
        Parameter::updateOrCreate([
            'name'  => 'recipe_spec_attributes',
            'value' => 'isMustard',
            'order' => 9,
            'is_internal' => true,
            'is_default' => false
        ]);
        Parameter::updateOrCreate([
            'name'  => 'recipe_spec_attributes',
            'value' => 'isSesame',
            'order' => 10,
            'is_internal' => true,
            'is_default' => false
        ]);

    }
}
