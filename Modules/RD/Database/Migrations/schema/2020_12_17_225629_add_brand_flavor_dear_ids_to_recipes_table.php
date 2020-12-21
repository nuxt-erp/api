<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBrandFlavorDearIdsToRecipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('rd_recipes', function (Blueprint $table) {
            $table->foreignId('brand_id')->nullable()->constrained('inv_brands')->onDelete('set null');
            $table->foreignId('flavor_id')->nullable()->constrained('inv_flavors')->onDelete('set null');
            $table->string('dear_id')->nullable();
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
            $table->dropColumn('brand_id');
            $table->dropColumn('flavor_id');
            $table->dropColumn('dear_id');
        });
    }
}
