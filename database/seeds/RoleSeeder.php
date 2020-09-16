<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
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
            'code' => 'admin',
            'name' => 'Admin'
        ]);
    }
}
