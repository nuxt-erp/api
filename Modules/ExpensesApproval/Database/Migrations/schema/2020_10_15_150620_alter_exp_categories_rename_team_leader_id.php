<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterExpCategoriesRenameTeamLeaderId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('exp_ap_categories', function (Blueprint $table) {
            $table->renameColumn('team_leader_id', 'lead_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('exp_ap_categories', function (Blueprint $table) {
            $table->renameColumn('lead_id', 'team_leader_id');
        });
    }
}
