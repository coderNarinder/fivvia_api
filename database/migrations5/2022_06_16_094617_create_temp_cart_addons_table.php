<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempCartAddonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_cart_addons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cart_id');
            $table->unsignedBigInteger('cart_product_id')->index('temp_cart_addons_cart_product_id_foreign');
            $table->unsignedBigInteger('addon_id')->index('temp_cart_addons_addon_id_foreign');
            $table->unsignedBigInteger('option_id')->nullable()->index('temp_cart_addons_option_id_foreign');
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
        Schema::dropIfExists('temp_cart_addons');
    }
}
