<?php

namespace Modules\RD\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\RD\Entities\Project;
use Modules\RD\Entities\ProjectAttributes;
use Modules\RD\Entities\ProjectSamples;
use Modules\RD\Entities\Recipe;
use Modules\RD\Entities\RecipeAttributes;
use Modules\RD\Entities\RecipeItems;
use Modules\RD\Entities\RecipeProposalItems;
use Modules\RD\Entities\RecipeProposals;


class RDDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        Project::updateOrCreate([
            'author_id'  => 1,
            'customer_id'  => 1,
            'status'  => 'In Progress',
            'code'  => 'MHR42O9',
            'comment'  => 'This project is for a very important client.',
            'start_at'  => '2020-09-11',
        ]);
        ProjectAttributes::updateOrCreate([
            'project_id'  => 1,
            'attribute_id'  => 1
        ]);
        ProjectSamples::updateOrCreate([
            'recipe_id'  => 1,
            'project_id'  => 1,
            'assignee_id'  => 1,
            'name'  => 'Apple Grape',
            'status'  => 'In Progress',
            'target_cost'  => 13.99,
            'feedback'  => 'There is too much grape, not enough apple.',
            'comment'  => 'Recipe needs to be reworked before sent to client.'
        ]);
        Recipe::updateOrCreate([
            'author_id'  => 1,
            'last_updater_id'  => 1,
            'approver_id'  => 1,
            'type_id'  => 1,
            'product_id'  => 1,
            'name'  => 'Apple Grape Recipe',
            'category'  => 'E-Liquid',
            'total'  => '',
            'code'  => 'O90H2J8',
            'cost'  => 8.76,
            'version'  => 1
        ]);
        RecipeAttributes::updateOrCreate([
            'recipe_id'  => 1,
            'attribute_id'  => 1
        ]);
        RecipeItems::updateOrCreate([
            'product_id'  => 1,
            'recipe_id'  => 1,
            'quantity' => 0.23,
            'percent' => 0.12,
            'cost' => 0.9
        ]);
        RecipeProposalItems::updateOrCreate([
            'recipe_proposal_id'  => 1,
            'recipe_item_id'  => 1,
            'quantity' => 0.54,
            'percent' => 0.32
        ]);
        RecipeProposals::updateOrCreate([
            'recipe_id'  => 1,
            'author_id'  => 1,
            'approver_id' => 1,
            'status' => 'In Progress',
            'comment' => 'Recipe in progress, awaiting testing.'
        ]);
        // $this->call("OthersTableSeeder");
    }
}
