<?php

namespace Modules\ExpensesApproval\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ExpensesApprovalDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(ExpensesRuleTableSeeder::class);
        $this->call(RoleTableSeeder::class);
        $this->call(ParameterTableSeeder::class);
        $this->call(UserTableSeeder::class);
    }
}
