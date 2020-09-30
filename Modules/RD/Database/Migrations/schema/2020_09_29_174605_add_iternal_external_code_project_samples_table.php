<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIternalExternalCodeProjectSamplesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('rd_project_samples', function (Blueprint $table) {
            $table->string('internal_code');
            $table->string('external_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('rd_project_samples', function (Blueprint $table) {
            $table->dropColumn('internal_code');
            $table->dropColumn('external_code');
        });
    }
}
