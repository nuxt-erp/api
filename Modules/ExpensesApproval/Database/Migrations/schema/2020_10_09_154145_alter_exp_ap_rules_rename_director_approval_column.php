<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterExpApRulesRenameDirectorApprovalColumn extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('exp_ap_rules', function (Blueprint $table) {
            $table->renameColumn('director_approval', 'sponsor_approval');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('exp_ap_rules', function (Blueprint $table) {
            $table->renameColumn('sponsor_approval', 'director_approval');
        });
    }
}
