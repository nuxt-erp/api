<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
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
            'company_id'    => 1,
            'email'         => 'admin@email.com',
            'password'      => bcrypt('1234')
        ]);
        $admin->setAsAdmin();

        $dear = User::create([
            'name'          => 'user',
            'company_id'    => 1,
            'email'         => 'user@email.com',
            'password'      => bcrypt('1234')
        ]);
        $dear->setAsUser();
    }
}
