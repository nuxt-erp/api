<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDearOptionsToConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('configs', function (Blueprint $table) {
            $table->boolean('dear_automatic_sync')->default(1);
            $table->boolean('dear_sync_existing_brands')->default(1);
            $table->boolean('dear_sync_existing_categories')->default(1);
            $table->boolean('dear_sync_existing_products')->default(1);
            $table->boolean('dear_sync_existing_product_sizes')->default(1);
            $table->boolean('dear_sync_existing_product_strengths')->default(1);
            $table->boolean('dear_sync_existing_availabilities')->default(1);
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
            $table->dropColumn('dear_automatic_sync');
            $table->dropColumn('dear_sync_existing_brands');
            $table->dropColumn('dear_sync_existing_categories');
            $table->dropColumn('dear_sync_existing_products');
            $table->dropColumn('dear_sync_existing_product_sizes');
            $table->dropColumn('dear_sync_existing_product_strengths');
            $table->dropColumn('dear_sync_existing_availabilities');
        });
    }
}
