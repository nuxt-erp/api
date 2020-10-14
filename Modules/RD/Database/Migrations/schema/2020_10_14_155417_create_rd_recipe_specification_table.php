<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRdRecipeSpecificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('rd_recipe_specification', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_sample_id')->constrained('rd_project_samples')->onDelete('set null');
            $table->foreignId('approver_id')->constrained('public.users')->onDelete('set null');
            $table->string('appearance');
            $table->string('aroma');
            $table->string('flavor');
            $table->string('viscosity');
            $table->string('specific_gravity');
            $table->string('flash_point');
            //shelf life (custom dropdown)
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
        Schema::connection('tenant')->dropIfExists('rd_recipe_specification');
    }
}
