<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCartVendorDeliveryFeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cart_vendor_delivery_fee', function (Blueprint $table) {
            $table->foreign(['cart_id'])->references(['id'])->on('carts')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['vendor_id'])->references(['id'])->on('vendors')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cart_vendor_delivery_fee', function (Blueprint $table) {
            $table->dropForeign('cart_vendor_delivery_fee_cart_id_foreign');
            $table->dropForeign('cart_vendor_delivery_fee_vendor_id_foreign');
        });
    }
}
