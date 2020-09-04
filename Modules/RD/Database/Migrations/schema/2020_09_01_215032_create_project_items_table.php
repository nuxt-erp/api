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
            $table->foreignId('recipe_id')->nullable()->constrained('rd_recipes')->onDelete('set null');
            $table->foreignId('project_id')->constrained('rd_projects')->onDelete('cascade');
            $table->foreignId('assignee_id')->nullable()->constrained('public.users')->onDelete('set null');

            $table->string('name')->nullable();
            $table->string('status');
            $table->float('target_cost', 10, 4)->nullable();
            $table->string('feedback')->nullable();
            $table->string('comment');

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
