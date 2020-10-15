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
            'code' => 'user',
            'name' => 'User'
        ]);

        Role::updateOrCreate([
            'code' => 'buyer',
            'name' => 'Buyer'
        ]);

        Role::updateOrCreate([
            'code' => 'sponsor',
            'name' => 'Sponsor'
        ]);

        Role::updateOrCreate([
            'code' => 'lead',
            'name' => 'Lead'
        ]);
    }
}
