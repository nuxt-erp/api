<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name'          => 'admin',
            'email'         => 'admin@email.com',
            'password'      => bcrypt('123456')
        ]);
        $admin->setRole('admin');

        $basic = User::create([
            'name'          => 'user',
            'email'         => 'user@email.com',
            'password'      => bcrypt('123456')
        ]);
        $basic->setRole('user');
    }
}
