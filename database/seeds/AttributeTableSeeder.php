<?php

use Illuminate\Database\Seeder;
use App\Models\Attribute;

class AttributeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Attribute::create([
            'name'          => 'Strength',
            'company_id'    => 1
        ]);

        Attribute::create([
            'name'          => 'Size',
            'company_id'    => 1
        ]);

        Attribute::create([
            'name'          => 'Variant',
            'company_id'    => 1
        ]);
    }
}
