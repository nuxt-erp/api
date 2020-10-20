<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterIngredientListRecipeSpecificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('rd_recipe_specification', function (Blueprint $table) {
            $table->text('ingredient_list')->change();
            $table->text('storage_conditions')->change();

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
            $table->string('ingredient_list')->change();
            $table->string('storage_conditions')->change();

        });
    }
}
