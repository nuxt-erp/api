<?php

namespace Modules\RD\Database\Seeders;
use Illuminate\Database\Seeder;
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
        PhaseRole::updateOrCreate([
            'phase_id' => 1,
            'role_id' => 6
        ], [
            'phase_id' => 1,
            'role_id' => 6
        ]);

        PhaseRole::updateOrCreate([
            'phase_id' => 2,
            'role_id' => 7
        ], [
            'phase_id' => 2,
            'role_id' => 7
        ]);

        PhaseRole::updateOrCreate([
            'phase_id' => 3,
            'role_id' => 9
        ], [
            'phase_id' => 3,
            'role_id' => 9
        ]);
            
        PhaseRole::updateOrCreate([
            'phase_id' => 4,
            'role_id' => 7
        ], [
            'phase_id' => 4,
            'role_id' => 7
        ]);

        PhaseRole::updateOrCreate([
            'phase_id' => 5,
            'role_id' => 6
        ], [
            'phase_id' => 5,
            'role_id' => 6
        ]);

        PhaseRole::updateOrCreate([
            'phase_id' => 6,
            'role_id' => 6
        ], [
            'phase_id' => 6,
            'role_id' => 6
        ]);

        PhaseRole::updateOrCreate([
            'phase_id' => 7,
            'role_id' => 6
        ], [
            'phase_id' => 7,
            'role_id' => 6
        ]);

    }
}
