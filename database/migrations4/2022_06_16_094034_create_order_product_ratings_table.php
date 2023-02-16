<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProductRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_product_ratings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_vendor_product_id')->nullable()->index('order_product_ratings_order_vendor_product_id_foreign');
            $table->unsignedBigInteger('order_id')->nullable()->index('order_product_ratings_order_id_foreign');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->decimal('rating', 4)->nullable();
            $table->string('review', 500)->nullable();
            $table->string('file', 500)->nullable();
            $table->timestamps();
            $table->enum('status', ['0', '1'])->default('1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_product_ratings');
    }
}
