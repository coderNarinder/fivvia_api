<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorSlotDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_slot_dates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vendor_id')->nullable()->index('vendor_slot_dates_vendor_id_foreign');
            $table->unsignedBigInteger('category_id')->nullable()->index('vendor_slot_dates_category_id_foreign');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->date('specific_date')->index();
            $table->tinyInteger('working_today')->default(1)->comment('1 - yes, 0 - no');
            $table->tinyInteger('dine_in')->default(0)->index()->comment('1 for yes, 0 for no');
            $table->tinyInteger('takeaway')->default(0)->index()->comment('1 for yes, 0 for no');
            $table->tinyInteger('delivery')->default(0)->index()->comment('1 for yes, 0 for no');
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
        Schema::dropIfExists('vendor_slot_dates');
    }
}
