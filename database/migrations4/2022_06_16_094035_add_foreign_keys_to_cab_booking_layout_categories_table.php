<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCabBookingLayoutCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cab_booking_layout_categories', function (Blueprint $table) {
            $table->foreign(['cab_booking_layout_id'])->references(['id'])->on('cab_booking_layouts')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['category_id'])->references(['id'])->on('categories')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cab_booking_layout_categories', function (Blueprint $table) {
            $table->dropForeign('cab_booking_layout_categories_cab_booking_layout_id_foreign');
            $table->dropForeign('cab_booking_layout_categories_category_id_foreign');
        });
    }
}
