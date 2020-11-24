<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBinIdToStockcountDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('inv_stock_count_details', function (Blueprint $table) {
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
        Schema::connection('tenant')->table('inv_stock_count_details', function (Blueprint $table) {
            $table->dropColumn('bin_id');
        });
    }
}
