<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterChangeDefaultShopifySyncSalesConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('configs', function (Blueprint $table) {
            $table->boolean('shopify_sync_sales')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('configs', function (Blueprint $table) {
            $table->boolean('shopify_sync_sales')->default(1)->change();
        });
    }
}
