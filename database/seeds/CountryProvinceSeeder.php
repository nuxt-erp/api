<?php

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\Province;

class CountryProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $canada = Country::firstOrCreate([
            'name' => 'Canada'
        ]);

        Province::updateOrcreate([
            'name'      => 'Ontario',
            'code'      => 'ON',
            'country_id'=> $canada->id
        ]);
    }
}
