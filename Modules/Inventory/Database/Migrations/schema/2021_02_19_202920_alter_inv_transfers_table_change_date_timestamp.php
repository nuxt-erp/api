<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInvTransfersTableChangeDateTimestamp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('inv_transfers', function (Blueprint $table) {
            $table->dateTime('pu_date')->nullable()->change();
            $table->dateTime('eta')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('inv_transfers', function (Blueprint $table) {
            $table->date('pu_date')->nullable();
            $table->date('eta')->nullable();   
        });
    }
}
