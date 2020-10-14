<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRdRecipeSpecificationItemsAttributes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('rd_recipe_specification_attributes', function (Blueprint $table) {
            $table->dropColumn('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('rd_recipe_specification_attributes', function (Blueprint $table) {
            $table->boolean('value')->default(0);
        });
    }
}
