<?php

use App\Models\Currency;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Inventory\Entities\ProductCustomPrice;

class AlterProductsCustomPricesRemoveCurrencyColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('inv_prod_custom_prices', function (Blueprint $table) {

            $custom_prices = ProductCustomPrice::whereNotNull('currency')->get();
            foreach ($custom_prices as $custom_price) {
                $currency_id = Currency::where('name', 'ILIKE', $custom_price->currency)->orWhere('code', 'ILIKE', $custom_price->currency)->pluck('id')->first();
                if($currency_id) {
                    $custom_price->currency_id = $currency_id;
                    $custom_price->save();
                }
            }

            $table->dropColumn('currency');
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
            $table->string('currency')->nullable();
        });
    }
}
