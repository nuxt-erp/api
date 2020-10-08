<?php

namespace Modules\RD\Database\Seeders;
use Illuminate\Database\Seeder;
use Modules\RD\Entities\Flow;
use Modules\RD\Entities\Phase;

class FlowTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        
        $phases = [
            'pending'            => Phase::updateOrCreate(['name' => 'pending'], ['name' => 'pending']),
            'in_progress'        => Phase::updateOrCreate(['name' => 'in progress'],  ['name' => 'in progress']),
            'waiting_approval'   => Phase::updateOrCreate(['name' => 'waiting approval'], ['name' => 'waiting approval']),
            'waiting_qc'         => Phase::updateOrCreate(['name' => 'waiting qc'], ['name' => 'waiting qc']),
            'sent'               => Phase::updateOrCreate(['name' => 'sent'], ['name' => 'sent']),
            'approved'           => Phase::updateOrCreate(['name' => 'approved'], ['name' => 'approved']),
            'rework'             => Phase::updateOrCreate(['name' => 'rework'], ['name' => 'rework']),
        ];
        Flow::updateOrCreate([
            'phase_id' => $phases['pending']->id,
            'next_phase_id' => $phases['in_progress']->id,
            'start' => 1,
            'end' => null
        ], [
            'phase_id' => $phases['pending']->id,
            'next_phase_id' => $phases['in_progress']->id,
            'start' => 1,
            'end' => null
        ]);

        Flow::updateOrCreate([
            'phase_id' => $phases['in_progress']->id,
            'next_phase_id' => $phases['waiting_approval']->id,
            'start' => null,
            'end' => null
        ], [
            'phase_id' => $phases['in_progress']->id,
            'next_phase_id' => $phases['waiting_approval']->id,
            'start' => 1,
            'end' => null
        ]);

        Flow::updateOrCreate([
            'phase_id' => $phases['waiting_approval']->id,
            'next_phase_id' => $phases['waiting_qc']->id,
            'start' => null,
            'end' => null
        ], [
            'phase_id' => $phases['waiting_approval']->id,
            'next_phase_id' => $phases['waiting_qc']->id,
            'start' => 1,
            'end' => null
        ]);

        Flow::updateOrCreate([
            'phase_id' => $phases['waiting_approval']->id,
            'next_phase_id' => $phases['in_progress']->id,
            'start' => null,
            'end' => null
        ], [
            'phase_id' => $phases['waiting_approval']->id,
            'next_phase_id' => $phases['in_progress']->id,
            'start' => 1,
            'end' => null
        ]);

        Flow::updateOrCreate([
            'phase_id' => $phases['waiting_qc']->id,
            'next_phase_id' => $phases['sent']->id,
            'start' => null,
            'end' => null
        ], [
            'phase_id' => $phases['waiting_qc']->id,
            'next_phase_id' => $phases['sent']->id,
            'start' => 1,
            'end' => null
        ]);

        Flow::updateOrCreate([
            'phase_id' => $phases['sent']->id,
            'next_phase_id' => $phases['approved']->id,
            'start' => null,
            'end' => 1
        ], [
            'phase_id' => $phases['sent']->id,
            'next_phase_id' => $phases['approved']->id,
            'start' => 1,
            'end' => null
        ]);

        Flow::updateOrCreate([
            'phase_id' => $phases['sent']->id,
            'next_phase_id' => $phases['rework']->id,
            'start' => null,
            'end' => null
        ],  [
            'phase_id' => $phases['sent']->id,
            'next_phase_id' => $phases['rework']->id,
            'start' => 1,
            'end' => null
        ]);

        Flow::updateOrCreate([
            'phase_id' => $phases['rework']->id,
            'next_phase_id' => $phases['in_progress']->id,
            'start' => null,
            'end' => null
        ],  [
            'phase_id' => $phases['rework']->id,
            'next_phase_id' => $phases['in_progress']->id,
            'start' => 1,
            'end' => null
        ]);

    }
}
