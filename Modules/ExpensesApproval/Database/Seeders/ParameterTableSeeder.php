<?php

namespace Modules\ExpensesApproval\Database\Seeders;

use App\Models\Parameter;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ParameterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Parameter::updateOrCreate([
            'name'  => 'expenses_approval_status',
            'value' => 'pending',
            'order' => 1,
            'description' => 'Pending Approval',
            'is_internal' => true,
            'is_default' => false,
        ]);

        Parameter::updateOrCreate([
            'name'  => 'expenses_approval_status',
            'value' => 'approved',
            'order' => 1,
            'description' => 'Approved',
            'is_internal' => true,
            'is_default' => false,
        ]);

        Parameter::updateOrCreate([
            'name'  => 'expenses_approval_status',
            'value' => 'purchased',
            'order' => 1,
            'description' => 'Purchased',
            'is_internal' => true,
            'is_default' => false,
        ]);

        Parameter::updateOrCreate([
            'name'  => 'expenses_approval_status',
            'value' => 'denied',
            'order' => 1,
            'description' => 'Denied',
            'is_internal' => true,
            'is_default' => false,
        ]);

    }
}
