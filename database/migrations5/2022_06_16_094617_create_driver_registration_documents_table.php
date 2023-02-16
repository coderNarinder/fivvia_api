<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverRegistrationDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_registration_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('file_type')->nullable();
            $table->string('name')->nullable();
            $table->timestamps();
            $table->integer('is_required')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('driver_registration_documents');
    }
}
