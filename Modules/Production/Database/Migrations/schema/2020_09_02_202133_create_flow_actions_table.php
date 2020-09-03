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

            $table->foreignId('flow_id')->constrained('prod_flows')->onDelete('cascade');
            $table->foreignId('phase_id')->constrained('prod_phases')->onDelete('set null');
            $table->foreignId('destination_phase_id')->nullable()->constrained('prod_phases')->onDelete('set null');
            $table->foreignId('destination_location_id')->nullable()->constrained('locations')->onDelete('set null');

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
