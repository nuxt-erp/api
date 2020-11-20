<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPurchaseDetailsAddBinId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('pur_purchase_details', function (Blueprint $table) {
            $table->foreignId('bin_id')->nullable()->constrained('inv_location_bins')->onDelete('set null');
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
            $table->dropColumn('bin_id');
        });
    }
}
