<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPurPurchaseDetailsTableAddAllocationCreated extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('pur_purchase_details', function (Blueprint $table) {
            $table->smallInteger('allocation_created')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('pur_purchase_details', function (Blueprint $table) {
            $table->dropColumn('allocation_created');
        });
    }
}