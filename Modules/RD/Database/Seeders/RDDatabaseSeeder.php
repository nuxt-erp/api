<?php

namespace Modules\RD\Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Inventory\Entities\Category;

class RDDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $env = env('APP_ENV');

        Model::unguard();
        $this->call(ParameterTableSeeder::class);
        $this->call(PhaseTableSeeder::class);
        $this->call(FlowTableSeeder::class);
        $this->call(PhaseRoleSeeder::class);
        $this->call(RoleTableSeeder::class);
        if($env === 'local') {
            $this->call(ProjectAndSamplesSeeder::class);
        }

        // for the recipe page
        Category::updateOrCreate([
            'name'      => 'Carrier',
            'is_enabled'=> 1
        ]);

        Category::updateOrCreate([
            'name'      => 'Raw Material',
            'is_enabled'=> 1
        ]);

    }
}
