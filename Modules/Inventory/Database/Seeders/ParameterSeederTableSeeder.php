<?php

namespace Modules\Inventory\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Parameter;

class ParameterSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Parameter::updateOrCreate(
            [
                'name'        => 'product_log_type',
                'value'       => 'Purchase'
            ],
            [
                'description' => 'PO-'
            ]
        );

        Parameter::updateOrCreate(
            [
                'name'        => 'product_log_type',
                'value'       => 'Stock Adjustment'
            ],
            [
                'description' => 'ST-'
            ]
        );

        Parameter::updateOrCreate(
            [
                'name'        => 'product_log_type',
                'value'       => 'Receiving'
            ],
            [
                'description' => 'RC-'
            ]
        );

        Parameter::updateOrCreate(
            [
                'name'        => 'product_log_type',
                'value'       => 'Sale'
            ],
            [
                'description' => 'SAL-'
            ]
        );

        Parameter::updateOrCreate(
            [
                'name'        => 'product_log_type',
                'value'       => 'Transfer'
            ],
            [
                'description' => 'TR-'
            ]
        );

        Parameter::updateOrCreate(
            [
                'name'        => 'product_log_type',
                'value'       => 'Stock Count'
            ],
            [
                'description' => 'ST-'
            ]
        );

        Parameter::updateOrCreate(
            [
                'name'        => 'product_log_type',
                'value'       => 'Stock Update'
            ],
            [
                'description' => 'SU-'
            ]
        );
    }
}
