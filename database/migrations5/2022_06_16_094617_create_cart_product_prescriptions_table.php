<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartProductPrescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_product_prescriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cart_id')->nullable()->index('cart_product_prescriptions_cart_id_foreign');
            $table->unsignedBigInteger('product_id')->nullable()->index('cart_product_prescriptions_product_id_foreign');
            $table->string('prescription')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('vendor_id')->nullable()->index('cart_product_prescriptions_vendor_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_product_prescriptions');
    }
}
