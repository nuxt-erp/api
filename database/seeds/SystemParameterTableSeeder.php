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
        // id, param_name, param_value, is_default, description, company_id
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
    }
}
