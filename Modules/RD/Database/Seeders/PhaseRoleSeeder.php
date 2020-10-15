<?php

namespace Modules\RD\Database\Seeders;
use Illuminate\Database\Seeder;
use Modules\RD\Entities\Phase;
use App\Models\Role;
use Modules\RD\Entities\PhaseRole;

class PhaseRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        PhaseRole::truncate();

        $phases = [
            'pending'            => Phase::updateOrCreate(['name' => 'pending'], ['name' => 'pending']),
            'assigned'           => Phase::updateOrCreate(['name' => 'assigned'],  ['name' => 'assigned']),
            'in_progress'        => Phase::updateOrCreate(['name' => 'in progress'],  ['name' => 'in progress']),
            'waiting_approval'   => Phase::updateOrCreate(['name' => 'waiting approval'], ['name' => 'waiting approval']),
            'waiting_qc'         => Phase::updateOrCreate(['name' => 'waiting qc'], ['name' => 'waiting qc']),
            'ready'              => Phase::updateOrCreate(['name' => 'ready'], ['name' => 'ready']),
            'sent'               => Phase::updateOrCreate(['name' => 'sent'], ['name' => 'sent']),
            'approved'           => Phase::updateOrCreate(['name' => 'approved'], ['name' => 'approved']),
            'rework'             => Phase::updateOrCreate(['name' => 'rework'], ['name' => 'rework']),
        ];
        $roles = [
            'rd_requester'             => Role::updateOrCreate(['code' => 'rd_requester'], ['code' => 'rd_requester', 'name' => 'RD Requester']),
            'rd_supervisor'            => Role::updateOrCreate(['code' => 'rd_supervisor'],  ['code' => 'rd_supervisor' , 'name' => 'RD Supervisor']),
            'rd_quality_control'       => Role::updateOrCreate(['code' => 'rd_quality_control'], ['code' => 'rd_quality_control', 'name' => 'RD Quality Control']),
            'rd_flavorist'             => Role::updateOrCreate(['code' => 'rd_flavorist'], ['code' => 'rd_flavorist' , 'name' => 'RD Flavorist']),
        ];

        PhaseRole::updateOrCreate([
            'phase_id' => $phases['pending']->id
        ], [
            'phase_id' => $phases['pending']->id,
            'role_id'=> $roles['rd_requester']->id
        ]);

        PhaseRole::updateOrCreate([
            'phase_id' => $phases['assigned']->id
        ], [
            'phase_id' => $phases['assigned']->id,
            'role_id' => $roles['rd_supervisor']->id
        ]);

        PhaseRole::updateOrCreate([
            'phase_id' => $phases['in_progress']->id
        ], [
            'phase_id' => $phases['in_progress']->id,
            'role_id' => $roles['rd_flavorist']->id
        ]);

        PhaseRole::updateOrCreate([
            'phase_id' => $phases['waiting_approval']->id

        ], [
            'phase_id' => $phases['waiting_approval']->id,
            'role_id' =>  $roles['rd_flavorist']->id
        ]);
            
        PhaseRole::updateOrCreate([
            'phase_id' => $phases['waiting_qc']->id
        ], [
            'phase_id' => $phases['waiting_qc']->id,
            'role_id' => $roles['rd_supervisor']->id
        ]);

        PhaseRole::updateOrCreate([
            'phase_id' => $phases['ready']->id
        ], [
            'phase_id' => $phases['ready']->id,
            'role_id' => $roles['rd_quality_control']->id
        ]);

        PhaseRole::updateOrCreate([
            'phase_id' => $phases['sent']->id
        ], [
            'phase_id' => $phases['sent']->id,
            'role_id'=> $roles['rd_requester']->id
        ]);

        PhaseRole::updateOrCreate([
            'phase_id' => $phases['approved']->id
        ], [
            'phase_id' => $phases['approved']->id,
            'role_id'=> $roles['rd_requester']->id
        ]);

        PhaseRole::updateOrCreate([
            'phase_id' => $phases['rework']->id
        ], [
            'phase_id' => $phases['rework']->id,
            'role_id'=> $roles['rd_requester']->id
        ]);

    }
}
