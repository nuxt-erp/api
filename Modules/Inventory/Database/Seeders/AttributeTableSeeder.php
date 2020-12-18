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

        Attribute::updateOrCreate(
            ['name' => 'Density'],
            ['code' => 'density']
        );

        Attribute::updateOrCreate(
            ['name' => 'Batch Code Prefix'],
            ['code' => 'batch_code_prefix']
        );

        Attribute::updateOrCreate(
            ['name' => 'Qty Per Tray'],
            ['code' => 'qty_per_tray']
        );

        Attribute::updateOrCreate(
            ['name' => 'Previous Name'],
            ['code' => 'previous_name']
        );

        Attribute::updateOrCreate(
            ['name' => 'Material'],
            ['code' => 'material']
        );

        Attribute::updateOrCreate(
            ['name' => 'Color'],
            ['code' => 'color']
        );

        Attribute::updateOrCreate(
            ['name' => 'Cap Color'],
            ['code' => 'cap_color']
        );
    }
}
