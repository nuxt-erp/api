<?php

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::create([
            'name'          => 'Valor Distributions',
            'country_id'    => 1,
            'province_id'   => 1,
            'city'          => 'Markham'
        ]);

    }
}
