<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationDistanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_distance', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('from_loc_id');
            $table->bigInteger('to_loc_id');
            $table->bigInteger('distance')->comment('in meters');
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
        Schema::dropIfExists('location_distance');
    }
}
