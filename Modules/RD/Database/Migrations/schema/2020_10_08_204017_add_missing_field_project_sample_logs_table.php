<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMissingFieldProjectSampleLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('rd_project_sample_logs', function (Blueprint $table) {
            $table->boolean('is_start')->default(0);
            $table->foreignId('recipe_id')->nullable()->constrained('rd_recipes')->onDelete('set null');
            $table->foreignId('project_id')->constrained('rd_projects')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('internal_code')->nullable();
            $table->string('external_code')->nullable();

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
            $table->dropColumn('is_start');
            $table->dropColumn('recipe_id');
            $table->dropColumn('project_id');
            $table->dropColumn('name');
            $table->dropColumn('internal_code');
            $table->dropColumn('external_code');
        });
    }
}
