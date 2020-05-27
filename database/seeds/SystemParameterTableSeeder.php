<?php

use Illuminate\Database\Seeder;
use App\Models\SystemParameter;

class SystemParameterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Stock count type
        $param = SystemParameter::create([
            'param_name'      => 'count_type',
            'param_value'     => 'Low Stock Check'
        ]);

        $param = SystemParameter::create([
            'param_name'      => 'count_type',
            'param_value'     => 'Weekly Count'
        ]);

        $param = SystemParameter::create([
            'param_name'      => 'count_type',
            'param_value'     => 'Full Count'
        ]);

        // Carriers
        $param = SystemParameter::create([
            'param_name'      => 'carrier',
            'param_value'     => 'Valor'
        ]);

        $param = SystemParameter::create([
            'param_name'      => 'carrier',
            'param_value'     => 'UPS'
        ]);

        $param = SystemParameter::create([
            'param_name'      => 'carrier',
            'param_value'     => 'Fedex'
        ]);

        $param = SystemParameter::create([
            'param_name'      => 'carrier',
            'param_value'     => 'Purolator'
        ]);

        // Supplier Type
        $param = SystemParameter::create([
            'param_name'      => 'supplier_type',
            'param_value'     => 'US'
        ]);

        $param = SystemParameter::create([
            'param_name'      => 'supplier_type',
            'param_value'     => 'China'
        ]);

        // Shipment Type
        $param = SystemParameter::create([
            'param_name'      => 'shipment_type',
            'param_value'     => 'Express'
        ]);

        $param = SystemParameter::create([
            'param_name'      => 'shipment_type',
            'param_value'     => 'Standart'
        ]);

        $param = SystemParameter::create([
            'param_name'      => 'shipment_type',
            'param_value'     => 'Skid'
        ]);

        // Package type
        $param = SystemParameter::create([
            'param_name'      => 'package_type',
            'param_value'     => 'Skid'
        ]);

        $param = SystemParameter::create([
            'param_name'      => 'package_type',
            'param_value'     => 'Box'
        ]);

    }
}
