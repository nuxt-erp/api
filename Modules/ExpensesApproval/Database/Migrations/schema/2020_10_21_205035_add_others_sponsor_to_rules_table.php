<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOthersSponsorToRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('exp_ap_rules', function (Blueprint $table) {
            $table->boolean('others_sponsor_approval')->default(0);
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
            $table->dropColumn('others_sponsor_approval');
        });
    }
}
