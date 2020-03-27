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
            'name'      => 'admin',
            'email'     => 'admin@valordistributions.com',
            'password'  => bcrypt('cvladmin')
        ]);
        $admin->setAsAdmin();

        $dear = User::create([
            'name'      => 'dear',
            'email'     => 'dear@valordistributions.com',
            'password'  => bcrypt('cvladmin')
        ]);
        $dear->setAsUser();
    }
}
