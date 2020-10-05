<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNextPhaseRdFlowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('rd_flows', function (Blueprint $table) {
            $table->dropColumn('previous_flow_id');
            $table->dropColumn('next_flow_id');
            $table->dropColumn('name');
            $table->foreignId('next_phase_id')->nullable()->constrained('rd_phases')->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('rd_flows', function (Blueprint $table) {
            $table->bigInteger('previous_flow_id')->nullable();
            $table->bigInteger('next_flow_id')->nullable();
            $table->string('name');
            $table->dropColumn('next_phase_id');
        });
    }
}
