<?php

namespace Modules\Purchase\Database\Seeders;

use App\Models\Parameter;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ParameterSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // value fix
        $low_stock = Parameter::where('name', 'count_type')->where('value', 'Low Stock Check')->first();
        if($low_stock){
            $low_stock->value       = 'low_stock_check';
            $low_stock->description = 'Low Stock Check';
            $low_stock->save();
        }
        else{
            $low_stock = Parameter::updateOrCreate(
                [
                    'name'          => 'count_type',
                    'value'         => 'low_stock_check'
                ],
                [
                'description'   => 'Low Stock Check'
                ]
            );
        }

        // value fix
        $weekly_count = Parameter::where('name', 'count_type')->where('value', 'Weekly Count')->first();
        if($weekly_count){
            $weekly_count->value       = 'weekly_count';
            $weekly_count->description = 'Weekly Count';
            $weekly_count->save();
        }
        else{
            $weekly_count = Parameter::updateOrCreate(
                [
                    'name'          => 'count_type',
                    'value'         => 'weekly_count'
                ],
                [
                    'description'   => 'Weekly Count'
                ]
            );
        }

        // value fix
        $full_count = Parameter::where('name', 'count_type')->where('value', 'Full Count')->first();
        if($full_count){
            $full_count->value       = 'full_count';
            $full_count->description = 'Full Count';
            $full_count->save();
        }
        else{
            $full_count = Parameter::updateOrCreate(
                [
                    'name'          => 'count_type',
                    'value'         => 'full_count'
                ],
                [
                    'description'   => 'Full Count'
                ]
            );
        }

        // Carriers
        $param = Parameter::updateOrCreate([
            'name'      => 'carrier',
            'value'     => 'Valor'
        ]);

        $param = Parameter::updateOrCreate([
            'name'      => 'carrier',
            'value'     => 'UPS'
        ]);

        $param = Parameter::updateOrCreate([
            'name'      => 'carrier',
            'value'     => 'Fedex'
        ]);

        $param = Parameter::updateOrCreate([
            'name'      => 'carrier',
            'value'     => 'Purolator'
        ]);

        // Supplier Type
        $param = Parameter::updateOrCreate([
            'name'      => 'supplier_type',
            'value'     => 'US'
        ]);

        $param = Parameter::updateOrCreate([
            'name'      => 'supplier_type',
            'value'     => 'China'
        ]);

        // Shipment Type
        $param = Parameter::updateOrCreate([
            'name'      => 'shipment_type',
            'value'     => 'Express'
        ]);

        $param = Parameter::updateOrCreate([
            'name'      => 'shipment_type',
            'value'     => 'Standard'
        ]);

        $param = Parameter::updateOrCreate([
            'name'      => 'shipment_type',
            'value'     => 'Skid'
        ]);

        // Package type
        $param = Parameter::updateOrCreate([
            'name'      => 'package_type',
            'value'     => 'Skid'
        ]);

        $param = Parameter::updateOrCreate([
            'name'      => 'package_type',
            'value'     => 'Box'
        ]);

    }
}
