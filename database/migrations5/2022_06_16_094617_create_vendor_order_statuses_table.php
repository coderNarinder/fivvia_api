<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorOrderStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_order_statuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id')->nullable()->index('order_statuses_order_id_foreign');
            $table->unsignedBigInteger('order_vendor_id')->nullable();
            $table->unsignedBigInteger('order_status_option_id')->nullable()->index('order_statuses_order_status_option_id_foreign');
            $table->timestamps();
            $table->unsignedBigInteger('vendor_id')->nullable()->index('vendor_order_statuses_vendor_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_order_statuses');
    }
}
