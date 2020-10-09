<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLastVersionNotUniqueRecipeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('rd_recipes', function (Blueprint $table) {
            $table->dropUnique(['code', 'last_version']);
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
            $table->unique(['code', 'last_version']);
        });

    }
}
