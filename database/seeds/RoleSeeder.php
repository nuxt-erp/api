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

        Role::create([
            'code' => 'user',
            'name' => 'User'
        ]);

        Role::create([
            'code' => 'admin',
            'name' => 'Admin'
        ]);
    }
}
