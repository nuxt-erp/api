<?php

namespace Modules\Sales\Database\Seeders;

use App\Models\Parameter;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SeedParametersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // // financial_status
        // Parameter::updateOrCreate(
        //     ['name' => 'sales_financial_status'],
        //     ['code' => 'pending']
        // );

        // // fulfillment_status
        // Parameter::updateOrCreate(
        //     ['name' => 'sales_fulfillment_status'],
        //     ['code' => 'fulfilled']
        // );

        // Parameter::updateOrCreate(
        //     ['name' => 'sales_fulfillment_status'],
        //     ['code' => 'success']
        // );

        // Parameter::updateOrCreate(
        //     ['name' => 'sales_fulfillment_status'],
        //     ['code' => 'cancelled']
        // );

    }
}
