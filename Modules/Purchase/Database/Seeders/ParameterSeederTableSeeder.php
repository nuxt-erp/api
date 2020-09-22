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

        $param = Parameter::updateOrCreate([
            'name'      => 'count_type',
            'value'     => 'Low Stock Check'
        ]);

        $param = Parameter::updateOrCreate([
            'name'      => 'count_type',
            'value'     => 'Weekly Count'
        ]);

        $param = Parameter::updateOrCreate([
            'name'      => 'count_type',
            'value'     => 'Full Count'
        ]);

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
