<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCrossSellsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_cross_sells', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable()->index('product_cross_sells_product_id_foreign');
            $table->unsignedBigInteger('cross_product_id')->nullable()->index('product_cross_sells_cross_product_id_foreign');
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
        Schema::dropIfExists('product_cross_sells');
    }
}
