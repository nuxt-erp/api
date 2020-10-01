<?php

namespace Modules\RD\Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::updateOrCreate([
            'code' => 'rd_requester',
            'name' => 'RD Requester'
        ]);

        Role::updateOrCreate([
            'code' => 'rd_supervisor',
            'name' => 'RD Supervisor'
        ]);

        Role::updateOrCreate([
            'code' => 'rd_quality_control',
            'name' => 'RD Quality Control'
        ]);

        Role::updateOrCreate([
            'code' => 'rd_flavourist',
            'name' => 'RD Flavourist'
        ]);
    }
}
