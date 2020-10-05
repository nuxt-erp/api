<?php

namespace Modules\RD\Database\Seeders;
use Illuminate\Database\Seeder;
use Modules\RD\Entities\Phase;

class PhaseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Phase::updateOrCreate([
            'name' => 'pending'
        ], [
            'name' => 'pending'
        ]);

        Phase::updateOrCreate([
            'name' => 'in progress'
        ], [
            'name' => 'in progress'
        ]);

        Phase::updateOrCreate([
            'name' => 'waiting approval'
        ], [
            'name' => 'waiting approval'
        ]);

        Phase::updateOrCreate([
            'name' => 'waiting qc'
        ], [
            'name' => 'waiting qc'
        ]);
        
        Phase::updateOrCreate([
            'name' => 'sent'
        ], [
            'name' => 'sent'
        ]);

        Phase::updateOrCreate([
            'name' => 'approved'
        ], [
            'name' => 'approved'
        ]);

        Phase::updateOrCreate([
            'name' => 'rework'
        ], [
            'name' => 'rework'
        ]);

    }
}
