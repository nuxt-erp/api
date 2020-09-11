<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCategoriesAddBuyerId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('exp_ap_categories', function (Blueprint $table) {
            $table->foreignId('buyer_id')->nullable()->constrained('public.users')->onDelete('set null')->after('director_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('exp_ap_categories', function (Blueprint $table) {
            $table->dropColumn('buyer_id');
        });    
    }
}
