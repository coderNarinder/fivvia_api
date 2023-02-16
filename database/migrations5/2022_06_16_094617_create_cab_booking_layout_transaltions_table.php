<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCabBookingLayoutTransaltionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cab_booking_layout_transaltions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable();
            $table->unsignedBigInteger('cab_booking_layout_id')->nullable()->index('cab_booking_layout_transaltions_cab_booking_layout_id_foreign');
            $table->unsignedBigInteger('language_id')->nullable()->index('cab_booking_layout_transaltions_language_id_foreign');
            $table->timestamps();
            $table->longText('body_html')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cab_booking_layout_transaltions');
    }
}
