<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorOrderDispatcherStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_order_dispatcher_statuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('dispatcher_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable()->index('dispatcher_statuses_order_id_foreign');
            $table->unsignedBigInteger('dispatcher_status_option_id')->nullable()->index('dispatcher_statuses_dispatcher_status_option_id_foreign');
            $table->timestamps();
            $table->unsignedBigInteger('vendor_id')->nullable()->index('vendor_order_dispatcher_statuses_vendor_id_foreign');
            $table->enum('type', ['1', '2'])->default('1')->comment('1 : pickup , 2 : drop');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_order_dispatcher_statuses');
    }
}
