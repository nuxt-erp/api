<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesRepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('sales_reps', function (Blueprint $table) {

            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('phone_number', 20)->nullable();
            $table->string('mobile', 20)->nullable();
            $table->float('comission', 10, 4);
            $table->unsignedSmallInteger('is_default')->default(1);
            $table->foreignId('user_id')->nullable()->constrained('public.users')->onDelete('cascade');
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
        Schema::connection('tenant')->dropIfExists('sales_reps');
    }
}
