<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\Location;
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

        $markham_warehouse = Location::where('name' , 'ILIKE', 'Markham')->first();
        if(!$markham_warehouse){
            $markham_warehouse = Location::create(['name' => 'Markham']);
        }
        $edmonton_warehouse = Location::where('name' , 'ILIKE', 'Edmonton')->first();
        if(!$edmonton_warehouse){
            $edmonton_warehouse = Location::create(['name' => 'Edmonton']);
        }

        Province::updateOrCreate([
            'name'      => 'Newfoundland and Labrador',
            'code'      => 'NL',
            'country_id'=> $canada->id,
            // 'location_id' => $markham_warehouse->id

        ],
        [
            'location_id' => $markham_warehouse->id
        ]);
        Province::updateOrCreate([
            'name'      => 'Prince Edward Island',
            'code'      => 'PE',
            'country_id'=> $canada->id,
            // 'location_id' => $markham_warehouse->id

        ],
        [
            'location_id' => $markham_warehouse->id
        ]);
        Province::updateOrCreate([
            'name'      => 'Nova Scotia',
            'code'      => 'NS',
            'country_id'=> $canada->id,
            // 'location_id' => $markham_warehouse->id

        ],
        [
            'location_id' => $markham_warehouse->id
        ]);
        Province::updateOrCreate([
            'name'      => 'New Brunswick',
            'code'      => 'NB',
            'country_id'=> $canada->id,
            // 'location_id' => $markham_warehouse->id

        ],
        [
            'location_id' => $markham_warehouse->id
        ]);
        Province::updateOrCreate([
            'name'      => 'Quebec',
            'code'      => 'QC',
            'country_id'=> $canada->id,
            // 'location_id' => $markham_warehouse->id

        ],
        [
            'location_id' => $markham_warehouse->id
        ]);
        Province::updateOrCreate([
            'name'      => 'Ontario',
            'code'      => 'ON',
            'country_id'=> $canada->id,
            // 'location_id' => $markham_warehouse->id

        ],
        [
            'location_id' => $markham_warehouse->id
        ]);
        Province::updateOrCreate([
            'name'      => 'Manitoba',
            'code'      => 'MB',
            'country_id'=> $canada->id,
            // 'location_id' => $markham_warehouse->id

        ],
        [
            'location_id' => $markham_warehouse->id
        ]);
        Province::updateOrCreate([
            'name'      => 'Saskatchewan',
            'code'      => 'SK',
            'country_id'=> $canada->id,
            // 'location_id' => $markham_warehouse->id

        ],
        [
            'location_id' => $edmonton_warehouse->id
        ]);
        Province::updateOrCreate([
            'name'      => 'Alberta',
            'code'      => 'AB',
            'country_id'=> $canada->id,
            // 'location_id' => $edmonton_warehouse->id

        ],
        [
            'location_id' => $edmonton_warehouse->id
        ]);
        Province::updateOrCreate([
            'name'      => 'British Columbia',
            'code'      => 'BC',
            'country_id'=> $canada->id,
            // 'location_id' => $markham_warehouse->id

        ],
        [
            'location_id' => $markham_warehouse->id
        ]);
        Province::updateOrCreate([
            'name'      => 'Yukon',
            'code'      => 'YT',
            'country_id'=> $canada->id,
            // 'location_id' => $edmonton_warehouse->id

        ],
        [
            'location_id' => $edmonton_warehouse->id
        ]);
        Province::updateOrCreate([
            'name'      => 'Northwest Territories',
            'code'      => 'NT',
            'country_id'=> $canada->id,
            // 'location_id' => $edmonton_warehouse->id

        ],
        [
            'location_id' => $edmonton_warehouse->id
        ]);
        Province::updateOrCreate([
            'name'      => 'Nunavut',
            'code'      => 'NU',
            'country_id'=> $canada->id,
            // 'location_id' => $markham_warehouse->id

        ],
        [
            'location_id' => $markham_warehouse->id
        ]);
    }
}

