<?php

namespace Modules\RD\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\RD\Entities\Project;
use Modules\RD\Entities\ProjectSamples;

class ProjectAndSamplesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $project_1 = Project::updateOrCreate([
        //     'author_id' => 1,
        //     'customer_id' => 1,
        //     'status' => 'active',
        //     'comment' => 'STLTH wants 3 new flavours in their berry lineup, flavours need to be developed for next month.',
        //     'start_at' => $date = date("Y-m-d H:i:s", strtotime("2020-09-19 00:00:00")),
        //     'iteration' => 1
        // ], [
        //     'author_id' => 1,
        //     'customer_id' => 1,
        //     'status' => 'active',
        //     'comment' => 'STLTH wants 3 new flavours in their berry lineup, flavours need to be developed for next month.',
        //     'start_at' => $date = date("Y-m-d H:i:s", strtotime("2020-09-19 00:00:00")),
        //     'iteration' => 1
        // ]);

        // $project_2 = Project::updateOrCreate([
        //     'author_id' => 1,
        //     'customer_id' => 1,
        //     'status' => 'active',
        //     'comment' => 'Valor has requested alchem to make a christmas lineup for eliquids. Includes 2 flavours, due date is the 1st of December.',
        //     'start_at' => $date = date("Y-m-d H:i:s", strtotime("2020-10-07 00:00:00")),
        //     'iteration' => 1
        // ], [
        //     'author_id' => 1,
        //     'customer_id' => 1,
        //     'status' => 'active',
        //     'comment' => 'Valor has requested alchem to make a christmas lineup for eliquids. Includes 2 flavours, due date is the 1st of December.',
        //     'start_at' => $date = date("Y-m-d H:i:s", strtotime("2020-10-07 00:00:00")),
        //     'iteration' => 1
        // ]);

        // ProjectSamples::updateOrCreate([
        //     'project_id'         => $project_1->id,
        //     'phase_id'           => 1,
        //     'name'               => 'Berry Implosion',
        //     'status'             => 'pending',
        //     'target_cost'        => 9.99,
        //     'feedback'           => '',
        //     'comment'            => 'Needs to be an implosion of flavour. Add cherry to this flavour, customers loved the previous cherry flavour.',
        //     'external_code'      => 'EX-008'
        // ], [
        //     'project_id'         => $project_1->id,
        //     'phase_id'           => 1,
        //     'name'               => 'Berry Implosion',
        //     'status'             => 'pending',
        //     'target_cost'        => 9.99,
        //     'feedback'           => '',
        //     'comment'            => 'Needs to be an implosion of flavour. Add cherry to this flavour, customers loved the previous cherry flavour.',
        //     'external_code'      => 'EX-008'
        // ]);
        // ProjectSamples::updateOrCreate([
        //     'project_id'         => $project_1->id,
        //     'assignee_id'        => 1,
        //     'phase_id'           => 2,
        //     'status'             => 'in progress',
        //     'name'               => 'Acai Berry Lemonade',
        //     'target_cost'        => 12.99,
        //     'feedback'           => '',
        //     'comment'            => 'Try to match the acai refresher flavour from Starbucks. Add lemonade to this one.',
        //     'external_code'      => 'EX-009'
        // ], [
        //     'project_id'         => $project_1->id,
        //     'assignee_id'        => 1,
        //     'phase_id'           => 2,
        //     'status'             => 'in progress',
        //     'name'               => 'Acai Berry Lemonade',
        //     'target_cost'        => 12.99,
        //     'feedback'           => '',
        //     'comment'            => 'Try to match the acai refresher flavour from Starbucks. Add lemonade to this one.',
        //     'external_code'      => 'EX-009'
        // ]);
        // ProjectSamples::updateOrCreate([
        //     'project_id'         => $project_1->id,
        //     'assignee_id'        => 1,
        //     'phase_id'           => 3,
        //     'status'             => 'waiting approval',
        //     'name'               => 'Blueberry Bacon',
        //     'target_cost'        => 199.99,
        //     'feedback'           => '',
        //     'comment'            => 'Don\'t ask me why, people love our sweet and savoury breakfast flavours they\'re a hot commodity.',
        //     'external_code'      => 'EX-010'
        // ], [
        //     'project_id'         => $project_1->id,
        //     'assignee_id'        => 1,
        //     'phase_id'           => 3,
        //     'status'             => 'waiting approval',
        //     'name'               => 'Blueberry Bacon',
        //     'target_cost'        => 199.99,
        //     'feedback'           => '',
        //     'comment'            => 'Don\'t ask me why, people love our sweet and savoury breakfast flavours they\'re a hot commodity.',
        //     'external_code'      => 'EX-010'
        // ]);

    }
}
