<?php

use Illuminate\Database\Seeder;
use App\Models\Province;

class ProvincesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //id, name, short_name, country_id
        Province::create([
            'name'          => 'Ontario',
            'short_name'    => 'ON',
            'country_id'    => 1
        ]);
    }
}
