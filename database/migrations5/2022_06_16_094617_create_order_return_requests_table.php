<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderReturnRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_return_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_vendor_product_id')->index('order_return_requests_order_vendor_product_id_foreign');
            $table->unsignedBigInteger('order_id')->index('order_return_requests_order_id_foreign');
            $table->unsignedBigInteger('return_by')->index('order_return_requests_return_by_foreign');
            $table->string('reason', 220)->nullable();
            $table->text('coments')->nullable();
            $table->mediumText('reason_by_vendor')->nullable();
            $table->enum('status', ['Pending', 'Accepted', 'Rejected', 'On-Hold'])->default('Pending');
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
        Schema::dropIfExists('order_return_requests');
    }
}
