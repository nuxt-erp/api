<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('rd_flows', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('phase_id')->constrained('rd_phases')->onDelete('cascade');
            $table->bigInteger('previous_flow_id')->nullable();
            $table->bigInteger('next_flow_id')->nullable();
            $table->boolean('start')->nullable();
            $table->boolean('end')->nullable();
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
        Schema::connection('tenant')->dropIfExists('rd_flows');
    }
}