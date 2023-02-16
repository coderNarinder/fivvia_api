<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cart_id')->index('cart_products_cart_id_foreign');
            $table->unsignedBigInteger('product_id')->index('cart_products_product_id_foreign');
            $table->unsignedBigInteger('vendor_id')->nullable()->index('cart_products_vendor_id_foreign');
            $table->unsignedBigInteger('vendor_dinein_table_id')->nullable();
            $table->integer('quantity')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index('cart_products_created_by_foreign');
            $table->tinyInteger('status')->index()->comment('0-Active, 1-Blocked, 2-Deleted');
            $table->unsignedBigInteger('variant_id')->nullable()->index('cart_products_variant_id_foreign');
            $table->tinyInteger('is_tax_applied')->index()->comment('0-Yes, 1-No');
            $table->unsignedBigInteger('tax_rate_id')->nullable()->index('cart_products_tax_rate_id_foreign');
            $table->string('schedule_type', 100)->nullable();
            $table->dateTime('scheduled_date_time')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('tax_category_id')->nullable()->index('cart_products_tax_category_id_foreign');
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->bigInteger('luxury_option_id')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_products');
    }
}
