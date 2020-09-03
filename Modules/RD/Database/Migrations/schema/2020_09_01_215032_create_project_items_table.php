<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('rd_project_items', function (Blueprint $table) {
            $table->bigIncrements('id');

            // this should be nullable because sometimes we don't have the recipe for this
            $table->unsignedBigInteger('recipe_id')->nullable();
            $table->foreign('recipe_id')->references('id')->on('rd_recipes');

            $table->unsignedBigInteger('project_id');
            $table->foreign('project_id')->references('id')->on('rd_projects');

            $table->unsignedBigInteger('assignee_id')->nullable();
            $table->foreign('assignee_id')->references('id')->on('users');

            $table->string('name')->nullable();
            $table->string('status');
            $table->float('target_cost', 10, 4)->nullable();
            $table->string('feedback')->nullable();
            $table->string('comment')->nullable();

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
        Schema::connection('tenant')->dropIfExists('rd_project_items');
    }
}
