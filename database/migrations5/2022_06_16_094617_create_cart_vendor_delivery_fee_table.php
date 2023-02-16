<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartVendorDeliveryFeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_vendor_delivery_fee', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cart_id')->nullable()->index('cart_vendor_delivery_fee_cart_id_foreign');
            $table->unsignedBigInteger('vendor_id')->nullable()->index('cart_vendor_delivery_fee_vendor_id_foreign');
            $table->decimal('delivery_fee', 64, 4)->default(0);
            $table->enum('shipping_delivery_type', ['D', 'L', 'S', 'SR'])->default('D');
            $table->timestamps();
            $table->integer('courier_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_vendor_delivery_fee');
    }
}
