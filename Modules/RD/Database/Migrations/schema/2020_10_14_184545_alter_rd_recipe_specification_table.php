<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRdRecipeSpecificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('rd_recipe_specification', function (Blueprint $table) {
            $table->string('storage_conditions');
            $table->string('shelf_life');
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
            $table->dropColumn('storage_conditions');
            $table->dropColumn('shelf_life');
        });
    }
}
