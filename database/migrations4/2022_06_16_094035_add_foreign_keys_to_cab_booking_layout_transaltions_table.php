<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCabBookingLayoutTransaltionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cab_booking_layout_transaltions', function (Blueprint $table) {
            $table->foreign(['cab_booking_layout_id'])->references(['id'])->on('cab_booking_layouts')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['language_id'])->references(['id'])->on('languages')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cab_booking_layout_transaltions', function (Blueprint $table) {
            $table->dropForeign('cab_booking_layout_transaltions_cab_booking_layout_id_foreign');
            $table->dropForeign('cab_booking_layout_transaltions_language_id_foreign');
        });
    }
}
