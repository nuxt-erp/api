<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIngredientListRecipeSpecificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('rd_recipe_specification', function (Blueprint $table) {
            $table->string('ingredient_list');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('rd_recipe_specification', function (Blueprint $table) {
            $table->dropColumn('ingredient_list');
        });
    }
}
