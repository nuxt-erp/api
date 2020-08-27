<?php

namespace Modules\ExpensesApproval\Database\Seeders;

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
            'code' => 'buyer',
            'name' => 'Buyer'
        ]);

        Role::updateOrCreate([
            'code' => 'director',
            'name' => 'Director'
        ]);

        Role::updateOrCreate([
            'code' => 'team_leader',
            'name' => 'team Leader'
        ]);
    }
}
