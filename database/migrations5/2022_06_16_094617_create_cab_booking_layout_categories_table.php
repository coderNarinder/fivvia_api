<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCabBookingLayoutCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cab_booking_layout_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cab_booking_layout_id')->nullable()->index('cab_booking_layout_categories_cab_booking_layout_id_foreign');
            $table->unsignedBigInteger('category_id')->nullable()->index('cab_booking_layout_categories_category_id_foreign');
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
        Schema::dropIfExists('cab_booking_layout_categories');
    }
}
