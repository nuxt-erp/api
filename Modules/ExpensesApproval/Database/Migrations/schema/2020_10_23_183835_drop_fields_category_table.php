<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropFieldsCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('exp_ap_categories', function (Blueprint $table) {
            //$table->dropForeign(['sponsor_id']);
            $table->dropColumn('sponsor_id');
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
            $table->foreignId('sponsor_id')->constrained('public.users')->onDelete('cascade');
        });
    }
}
