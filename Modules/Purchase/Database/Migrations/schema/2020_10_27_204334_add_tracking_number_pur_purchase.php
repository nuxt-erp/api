<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTrackingNumberPurPurchase extends Migration
{
    public function up()
    {
        Schema::connection('tenant')->table('pur_purchases', function (Blueprint $table) {
            $table->string('tracking_number')->nullable();
        });
    }

    public function down()
    {
        Schema::connection('tenant')->table('pur_purchases', function (Blueprint $table) {
            $table->dropColumn('tracking_number');
        });
    }
}
