<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Inventory\Entities\ProductSuppliers;

class AlterSuppliersAddCurrencyId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('inv_suppliers', function (Blueprint $table) {
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
        Schema::connection('tenant')->table('inv_suppliers', function (Blueprint $table) {
            $suppliers = ProductSuppliers::whereNotNull('currency_id')->get();
            foreach ($suppliers as $supplier) {
                $supplier->currency = $supplier->currency->code;
                $supplier->save();
            }
            
            $table->dropColumn('currency_id');
        });
    }
}
