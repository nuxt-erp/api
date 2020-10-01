<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInvProductsPromosAddGiftProductId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('inv_product_promos', function (Blueprint $table) {
            $table->foreignId('gift_product_id')->nullable()->constrained('inv_products')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('inv_product_promos', function (Blueprint $table) {
            $table->dropColumn('gift_product_id');
        });
    }
}
