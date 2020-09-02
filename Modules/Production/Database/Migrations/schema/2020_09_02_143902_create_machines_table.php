<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMachinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('machines', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('flow_id')->nullable();
            $table->foreign('flow_id')->references('id')->on('flows');

            $table->string('name', 50)->unique();
            $table->unsignedBigInteger('capacity')->nullable();
            $table->unsignedInteger('working_hours')->nullable();
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
        Schema::dropIfExists('machines');
    }
}