<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('sal_discount_rules', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->foreignId('type_id')->nullable();
            $table->foreignId('discount_id')->constrained('sal_discounts')->onDelete('cascade');
            $table->foreignId('discount_application_id')->constrained('sal_discount_applications')->onDelete('cascade');
            $table->boolean('include')->default(0);
            $table->boolean('exclude')->default(0);
            $table->boolean('stackable')->default(0);
            $table->boolean('all_products')->default(0);
            $table->string('rule');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('sal_discount_rules');
    }
}
