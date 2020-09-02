<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('prod_phases', function (Blueprint $table) {

            $table->bigIncrements('id');

            $table->unsignedBigInteger('operation_id');
            $table->foreign('operation_id')->references('id')->on('prod_operations');

            $table->string('name')->unique();

            $table->tinyInteger('start')->nullable();

            $table->tinyInteger('end')->nullable();

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
        Schema::connection('tenant')->dropIfExists('prod_phases');
    }
}
