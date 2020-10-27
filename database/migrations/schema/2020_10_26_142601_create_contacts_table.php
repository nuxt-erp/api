
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entity_id')->nullable()->onDelete('set null');
            $table->string('entity_type');
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('phone_number', 20)->nullable();
            $table->string('mobile', 20)->nullable();
            $table->unsignedSmallInteger('is_default')->default(0);
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
        Schema::connection('tenant')->dropIfExists('contacts');
    }
}
