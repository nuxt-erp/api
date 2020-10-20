<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategorySponsorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('exp_ap_category_sponsors', function (Blueprint $table) {
            $table->id();

            $table->foreignId('expenses_category_id')->constrained('exp_ap_categories')->onDelete('cascade');
            $table->foreignId('sponsor_id')->constrained('public.users')->onDelete('cascade');
            $table->boolean('is_primary')->default(0);

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
        Schema::connection('tenant')->dropIfExists('exp_ap_category_sponsors');
    }
}
