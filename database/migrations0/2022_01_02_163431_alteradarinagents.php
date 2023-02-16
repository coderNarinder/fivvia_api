<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Alteradarinagents extends Migration
{
   /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agents', function (Blueprint $table) {
           $table->string('vehicle_plate_number',50)->nullable();
           $table->string('aadhar_card_number',50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agents', function (Blueprint $table) {
             $table->dropColumn('aadhar_card_number');
             $table->dropColumn('vehicle_plate_number');
             
        });
    }
}
