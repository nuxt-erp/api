<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlowActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('prod_flow_actions', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('flow_id');
            $table->foreign('flow_id')->references('id')->on('prod_flows');

            $table->unsignedBigInteger('phase_id');
            $table->foreign('phase_id')->references('id')->on('prod_phases');

            $table->unsignedBigInteger('destination_phase_id')->nullable();
            $table->foreign('destination_phase_id')->references('id')->on('prod_phases');

            $table->unsignedBigInteger('destination_location_id')->nullable();
            $table->foreign('destination_location_id')->references('id')->on('locations');

            $table->string('name');

            $table->unique(['flow_id', 'name']);

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
        Schema::connection('tenant')->dropIfExists('prod_flow_actions');
    }
}
