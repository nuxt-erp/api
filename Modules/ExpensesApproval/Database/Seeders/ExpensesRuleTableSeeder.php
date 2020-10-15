<?php

namespace Modules\ExpensesApproval\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\ExpensesApproval\Entities\ExpensesRule;

class ExpensesRuleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        ExpensesRule::updateOrCreate([
            'name' => 'Small expenses',
            'lead_approval' => false,
            'sponsor_approval' => false,
            'start_value' => 0.00,
            'end_value' => 50.00
        ]);

        ExpensesRule::updateOrCreate([
            'name' => 'Medium expenses',
            'lead_approval' => true,
            'sponsor_approval' => false,
            'start_value' => 50.00,
            'end_value' => 500.00
        ]);

        ExpensesRule::updateOrCreate([
            'name' => 'Big expenses',
            'lead_approval' => true,
            'sponsor_approval' => true,
            'start_value' => 500.00,
            'end_value' => null
        ]);

    }
}
