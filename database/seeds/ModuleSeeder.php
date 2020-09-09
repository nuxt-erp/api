<?php

use App\Models\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Module::updateOrCreate([
            'name'  => 'Inventory'
        ]);

        Module::updateOrCreate([
            'name'  => 'Sales'
        ]);

<<<<<<< HEAD
<<<<<<< HEAD
        Module::updateOrCreate([
            'name'  => 'RD'
        ]);

        Module::updateOrCreate([
            'name'  => 'Production'
=======




=======
>>>>>>> origin/master
        Module::updateOrCreate([
            'name'  => 'RD'
>>>>>>> origin/master
        ]);

        Module::updateOrCreate([
            'name'  => 'Production'
        ]);
    }
}
