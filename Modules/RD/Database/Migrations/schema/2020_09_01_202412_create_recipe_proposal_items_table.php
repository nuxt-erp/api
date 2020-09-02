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
        Schema::connection('tenant')->create('rd_recipe_proposal_items', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('recipe_proposal_id');
            $table->foreign('recipe_proposal_id')->references('id')->on('rd_recipe_proposals');

            $table->unsignedBigInteger('recipe_item_id');
            $table->foreign('recipe_item_id')->references('id')->on('rd_recipe_items');

            $table->unsignedDecimal('quantity', 10, 4)->nullable();
            $table->unsignedDecimal('percent', 10, 4)->nullable();

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
        Schema::connection('tenant')->dropIfExists('rd_recipe_proposal_items');
    }
}
