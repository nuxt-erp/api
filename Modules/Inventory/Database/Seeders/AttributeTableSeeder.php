<?php

namespace Modules\Inventory\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Inventory\Entities\Attribute;

class AttributeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        Attribute::updateOrCreate(
            ['name' => 'Strength'],
            ['code' => 'str']
        );

        Attribute::updateOrCreate(
            ['name' => 'Size'],
            ['code' => 'size']
        );

        Attribute::updateOrCreate(
            ['name' => 'Variant'],
            ['code' => 'variant']
        );

        // Attribute::updateOrCreate(
        //     ['name' => 'Size'],
        //     ['code' => 'size']
        // );
    }
}
