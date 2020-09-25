<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInvProductSuppliersTableAddProductNameProductSkuCol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('inv_suppliers', function (Blueprint $table) {
            $table->string('product_name');
            $table->string('product_sku');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('inv_suppliers', function (Blueprint $table) {
            $table->dropColumn('product_name');
            $table->dropColumn('product_sku');
        });
    }
}