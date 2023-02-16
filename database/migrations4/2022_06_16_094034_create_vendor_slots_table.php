<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_slots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vendor_id')->nullable()->index('vendor_slots_vendor_id_foreign');
            $table->unsignedBigInteger('category_id')->nullable()->index('vendor_slots_category_id_foreign');
            $table->unsignedBigInteger('geo_id')->nullable();
            $table->time('start_time')->nullable()->index();
            $table->time('end_time')->nullable()->index();
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
        Schema::dropIfExists('vendor_slots');
    }
}
