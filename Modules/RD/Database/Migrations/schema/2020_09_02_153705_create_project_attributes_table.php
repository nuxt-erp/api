<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('rd_project_attributes', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->foreignId('project_id')->constrained('rd_projects')->onDelete('cascade');
            $table->foreignId('attribute_id')->constrained('parameters')->onDelete('cascade');

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
        Schema::connection('tenant')->dropIfExists('rd_project_attributes');
    }
}
