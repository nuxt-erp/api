<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInvReceivingDetailRenameQty extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('inv_receiving_details', function (Blueprint $table) {
            $table->renameColumn('qty', 'qty_allocated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('inv_receiving_details', function (Blueprint $table) {
            $table->renameColumn('qty_allocated', 'qty');
        });}
}
