<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverGeosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_geos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('geo_id')->nullable()->index('driver_geos_geo_id_foreign');
            $table->unsignedBigInteger('driver_id')->nullable()->index('driver_geos_driver_id_foreign');
            $table->unsignedBigInteger('team_id')->nullable()->index('driver_geos_team_id_foreign');
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
        Schema::dropIfExists('driver_geos');
    }
}
