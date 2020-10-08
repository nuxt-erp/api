<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUpdaterIdProjectSampleLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('rd_project_sample_logs', function (Blueprint $table) {
            $table->foreignId('updater_id')->nullable()->constrained('public.users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('rd_project_sample_logs', function (Blueprint $table) {
            $table->dropColumn('updater_id');
        });
    }
}
