<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFixPhaseIdProjectSamplesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('rd_project_samples', function (Blueprint $table) {
            $table->foreignId('phase_id')->nullable()->constrained('rd_phases')->onDelete('set null')->after('assignee_id');
            $table->dropColumn('flow_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('rd_project_samples', function (Blueprint $table) {
            $table->foreignId('flow_id')->nullable()->constrained('rd_flows')->onDelete('set null')->after('assignee_id');
            $table->dropColumn('phase_id');

        });
    }
}
