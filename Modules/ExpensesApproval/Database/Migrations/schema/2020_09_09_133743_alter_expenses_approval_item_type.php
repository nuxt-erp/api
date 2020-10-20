<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterExpensesApprovalItemType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('exp_ap_proposals', function (Blueprint $table) {
            //@todo undefined variable values
            $table->text('item')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('exp_ap_proposals', function (Blueprint $table) {
            $table->string('item')->change();
        });
    }
}
