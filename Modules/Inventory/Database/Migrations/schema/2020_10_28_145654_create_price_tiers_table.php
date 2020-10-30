<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriceTiersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('inv_price_tiers', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->double('markup', 10, 4)->nullable();
            $table->enum('markup_type', ['cost', 'msrp'])->nullable();
            $table->double('custom_price', 10, 4)->nullable();

            $table->foreignId('author_id')->nullable()->constrained('public.users')->onDelete('set null');
            $table->foreignId('last_updater_id')->nullable()->constrained('public.users')->onDelete('set null');

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
        Schema::connection('tenant')->dropIfExists('inv_price_tiers');
    }
}
