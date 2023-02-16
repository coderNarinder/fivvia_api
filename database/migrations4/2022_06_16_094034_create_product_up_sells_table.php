<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductUpSellsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_up_sells', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable()->index('product_up_sells_product_id_foreign');
            $table->unsignedBigInteger('upsell_product_id')->nullable()->index('product_up_sells_upsell_product_id_foreign');
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
        Schema::dropIfExists('product_up_sells');
    }
}
