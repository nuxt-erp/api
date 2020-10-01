<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInternalExternalProjectsSamplesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('rd_project_samples', function (Blueprint $table) {
            $table->string('internal_code')->nullable(true)->change();
            $table->string('external_code')->nullable(true)->change();
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
            $table->string('internal_code')->nullable(false)->change();
            $table->string('external_code')->nullable(false)->change();
        });
    }
}
