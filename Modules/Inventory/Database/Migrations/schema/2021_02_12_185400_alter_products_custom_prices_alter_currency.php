<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Inventory\Entities\ProductCustomPrice;

class AlterProductsCustomPricesAlterCurrency extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('inv_prod_custom_prices', function (Blueprint $table) {
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('inv_prod_custom_prices', function (Blueprint $table) {
            
            $custom_prices = ProductCustomPrice::whereNotNull('currency_id')->get();
            foreach ($custom_prices as $custom_price) {
                $custom_price->currency = $custom_price->currency->code;
                $custom_price->save();
            }
            
            $table->dropColumn('currency_id');
        });
    }
}
