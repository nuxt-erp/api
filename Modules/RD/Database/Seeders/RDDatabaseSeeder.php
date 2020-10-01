<?php

namespace Modules\RD\Database\Seeders;

use App\Models\Parameter;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;


class RDDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

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
            'value' => 'key',
            'order' => 1,
            'description' => 'Key',
            'is_internal' => true,
            'is_default' => false,
        ]);

        Parameter::updateOrCreate([
            'name'  => 'recipe_type',
            'value' => 'flavor',
            'order' => 2,
            'description' => 'Flavor',
            'is_internal' => true,
            'is_default' => false,
        ]);

        Parameter::updateOrCreate([
            'name'  => 'recipe_type',
            'value' => 'solution',
            'order' => 3,
            'description' => 'Solution',
            'is_internal' => true,
            'is_default' => false,
        ]);


        $this->call(RoleTableSeeder::class);

    }
}
