<?php

namespace Modules\Sales\Database\Seeders;
use App\Models\SalesRep;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SalesRepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        SalesRep::updateOrCreate(
            ['name' => 'Frankie'],
            [
                'email' => 'andrew@valordistributions.com',
                'comission' => 0.0
            ]
        );
        SalesRep::updateOrCreate(
            ['name' => 'Tom'],
            [
                'email' => 'thomas.soumbos@valordistributions.com',
                'comission' => 0.0
            ]
        );
        SalesRep::updateOrCreate(
            ['name' => 'Kristof'],
            [
                'email' => 'kristof@valordistributions.com',
                'comission' => 0.0
            ]
        );
        SalesRep::updateOrCreate(
            ['name' => 'Eddy'],
            [
                'email' => 'edmond.siu@valordistributions.com',
                'comission' => 0.0
            ]
        );

    }
}
