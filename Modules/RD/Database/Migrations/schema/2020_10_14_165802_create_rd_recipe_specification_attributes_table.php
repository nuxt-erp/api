<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRdRecipeSpecificationAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('rd_recipe_specification_attributes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('recipe_specification_id')->constrained('rd_recipe_specification')->onDelete('cascade');
            $table->foreignId('attribute_id')->constrained('parameters')->onDelete('cascade');
            $table->boolean('value')->default(0);
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
        Schema::connection('tenant')->dropIfExists('rd_recipe_specification_attributes');
    }
}
