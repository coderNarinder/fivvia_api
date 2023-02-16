<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 60)->index();
            $table->text('description')->nullable();
            $table->text('geo_array')->nullable();
            $table->smallInteger('zoom_level');
            $table->string('client_id', 10)->nullable()->index('geos_client_id_foreign');
            $table->timestamps();
            $table->geometry('polygon')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('geos');
    }
}
