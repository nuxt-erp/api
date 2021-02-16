<?php

use App\Models\Currency;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Inventory\Entities\ProductSuppliers;

class AlterSuppliersRemoveCurrencyColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('inv_suppliers', function (Blueprint $table) {
            $suppliers = ProductSuppliers::whereNotNull('currency')->get();
            foreach ($suppliers as $supplier) {
                lad($supplier);
                $currency_id = Currency::where('name', 'ILIKE', $supplier->currency)->orWhere('code', 'ILIKE', $supplier->currency)->pluck('id')->first();
                if($currency_id) {
                    $supplier->currency_id = $currency_id;
                    $supplier->save();
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
        Schema::connection('tenant')->table('inv_suppliers', function (Blueprint $table) {
            $table->string('currency')->nullable();
        });
    }
}
