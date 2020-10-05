<?php

namespace Modules\RD\Database\Seeders;
use Illuminate\Database\Seeder;
use Modules\RD\Entities\Flow;

class FlowTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        Flow::updateOrCreate([
            'phase_id' => 1,
            'next_phase_id' => 2,
            'start' => 1,
            'end' => null
        ], [
            'phase_id' => 1,
            'next_phase_id' => 2,
            'start' => 1,
            'end' => null
        ]);

        Flow::updateOrCreate([
            'phase_id' => 2,
            'next_phase_id' => 3,
            'start' => null,
            'end' => null
        ], [
            'phase_id' => 2,
            'next_phase_id' => 3,
            'start' => null,
            'end' => null
        ]);

        Flow::updateOrCreate([
            'phase_id' => 3,
            'next_phase_id' => 4,
            'start' => null,
            'end' => null
        ], [
            'phase_id' => 3,
            'next_phase_id' => 4,
            'start' => null,
            'end' => null
        ]);

        Flow::updateOrCreate([
            'phase_id' => 3,
            'next_phase_id' => 2,
            'start' => null,
            'end' => null
        ], [
            'phase_id' => 3,
            'next_phase_id' => 2,
            'start' => null,
            'end' => null
        ]);

        Flow::updateOrCreate([
            'phase_id' => 4,
            'next_phase_id' => 5,
            'start' => null,
            'end' => null
        ], [
            'phase_id' => 4,
            'next_phase_id' => 5,
            'start' => null,
            'end' => null
        ]);

        Flow::updateOrCreate([
            'phase_id' => 5,
            'next_phase_id' => 6,
            'start' => null,
            'end' => 1
        ], [
            'phase_id' => 5,
            'next_phase_id' => 6,
            'start' => null,
            'end' => 1
        ]);

        Flow::updateOrCreate([
            'phase_id' => 5,
            'next_phase_id' => 7,
            'start' => null,
            'end' => null
        ], [
            'phase_id' => 5,
            'next_phase_id' => 7,
            'start' => null,
            'end' => null
        ]);

        Flow::updateOrCreate([
            'phase_id' => 7,
            'next_phase_id' => 2,
            'start' => null,
            'end' => null
        ], [
            'phase_id' => 7,
            'next_phase_id' => 2,
            'start' => null,
            'end' => null
        ]);

    }
}
