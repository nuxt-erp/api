<?php

namespace Modules\ExpensesApproval\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $buyer = User::updateOrCreate([
            'name'          => 'user',
            'email'         => 'user@email.com',
            'password'      => bcrypt('123456')
        ]);       
        $buyer->setRole('user');

        $buyer = User::updateOrCreate([
            'name'          => 'buyer',
            'email'         => 'buyer@email.com',
            'password'      => bcrypt('123456')
        ]);       
        $buyer->setRole('buyer');

        $director = User::updateOrCreate([
            'name'          => 'Director',
            'email'         => 'director@email.com',
            'password'      => bcrypt('123456')
        ]);       
        $director->setRole('director');

        $team_leader = User::updateOrCreate([
            'name'          => 'Team Leader',
            'email'         => 'team_leader@email.com',
            'password'      => bcrypt('123456')
        ]);       
        $team_leader->setRole('team_leader');
    }
}
