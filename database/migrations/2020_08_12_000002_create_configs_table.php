<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('country_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('province_id')->nullable()->constrained()->onDelete('set null');

            $table->string('contact_name')->nullable();
            $table->string('email')->unique();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('city', 60)->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('website')->nullable();

            $table->string('dear_id')->nullable();
            $table->string('dear_key')->nullable();
            $table->string('dear_url')->nullable();
            $table->string('shopify_key')->nullable();
            $table->string('shopify_password')->nullable();
            $table->string('shopify_store_name')->nullable();
            $table->string('shopify_location')->nullable();

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
        Schema::dropIfExists('configs');
    }
}
