<?php

namespace Database\Seeders;

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
            'name'      => 'Newfoundland and Labrador',
            'code'      => 'NL',
            'country_id'=> $canada->id
        ]);
        Province::updateOrcreate([
            'name'      => 'Prince Edward Island',
            'code'      => 'PE',
            'country_id'=> $canada->id
        ]);
        Province::updateOrcreate([
            'name'      => 'Nova Scotia',
            'code'      => 'NS',
            'country_id'=> $canada->id
        ]);
        Province::updateOrcreate([
            'name'      => 'New Brunswick',
            'code'      => 'NB',
            'country_id'=> $canada->id
        ]);
        Province::updateOrcreate([
            'name'      => 'Quebec',
            'code'      => 'QC',
            'country_id'=> $canada->id
        ]);
        Province::updateOrcreate([
            'name'      => 'Ontario',
            'code'      => 'ON',
            'country_id'=> $canada->id
        ]);
        Province::updateOrcreate([
            'name'      => 'Manitoba',
            'code'      => 'MB',
            'country_id'=> $canada->id
        ]);
        Province::updateOrcreate([
            'name'      => 'Saskatchewan',
            'code'      => 'SK',
            'country_id'=> $canada->id
        ]);
        Province::updateOrcreate([
            'name'      => 'Alberta',
            'code'      => 'AB',
            'country_id'=> $canada->id
        ]);
        Province::updateOrcreate([
            'name'      => 'British Columbia',
            'code'      => 'BC',
            'country_id'=> $canada->id
        ]);
        Province::updateOrcreate([
            'name'      => 'Yukon',
            'code'      => 'YT',
            'country_id'=> $canada->id
        ]);
        Province::updateOrcreate([
            'name'      => 'Northwest Territories',
            'code'      => 'NT',
            'country_id'=> $canada->id
        ]);
        Province::updateOrcreate([
            'name'      => 'Nunavut',
            'code'      => 'NU',
            'country_id'=> $canada->id
        ]);
    }
}

