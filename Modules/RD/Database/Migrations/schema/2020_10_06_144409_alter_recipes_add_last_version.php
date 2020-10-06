<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRecipesAddLastVersion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('rd_recipes', function (Blueprint $table) {
            $table->boolean('last_version')->default(1);
            $table->unique(['code', 'last_version']);
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
            $table->dropUnique(['code', 'last_version']);
            $table->dropColumn('last_version');
        });
    }
}
