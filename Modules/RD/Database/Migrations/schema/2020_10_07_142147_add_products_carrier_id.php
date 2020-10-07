<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProductsCarrierId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('rd_recipes', function (Blueprint $table) {
            $table->foreignId('carrier_id')->nullable()->constrained('inv_products')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('rd_recipes', function (Blueprint $table) {
            $table->dropForeign(['carrier_id']);
            $table->dropColumn('carrier_id');
        });
    }
}
