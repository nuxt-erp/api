<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecipeProposalItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipe_proposal_items', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('recipe_proposal_id');
            $table->foreign('recipe_proposal_id')->references('id')->on('recipe_proposals');

            $table->unsignedBigInteger('recipe_item_id');
            $table->foreign('recipe_item_id')->references('id')->on('recipe_items');

            $table->unsignedDecimal('percent', 10, 4);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recipe_proposal_items');
    }
}