<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('prod_flows', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('first_phase_id')->nullable();
            $table->foreign('first_phase_id')->references('id')->on('prod_phases');

            $table->string('name')->unique();

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
        Schema::connection('tenant')->dropIfExists('prod_flows');
    }
}
