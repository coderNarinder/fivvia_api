<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderVendorProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_vendor_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id')->nullable()->index('order_products_order_id_foreign');
            $table->unsignedBigInteger('product_id')->nullable()->index('order_products_product_id_foreign');
            $table->unsignedBigInteger('order_vendor_id')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('product_name')->nullable();
            $table->string('image')->nullable();
            $table->decimal('price')->nullable();
            $table->string('taxable_amount')->nullable();
            $table->unsignedBigInteger('vendor_id')->nullable()->index('order_products_vendor_id_foreign');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('variant_id')->nullable()->index('order_products_variant_id_foreign');
            $table->unsignedBigInteger('tax_category_id')->nullable()->index('order_products_tax_category_id_foreign');
            $table->timestamps();
            $table->unsignedBigInteger('category_id')->nullable()->index('order_vendor_products_category_id_foreign');
            $table->string('product_dispatcher_tag')->nullable();
            $table->string('schedule_type', 100)->nullable();
            $table->dateTime('scheduled_date_time')->nullable();
            $table->string('product_variant_sets', 500)->nullable();
            $table->text('user_product_order_form')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_vendor_products');
    }
}
