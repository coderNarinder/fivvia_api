<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToOrderVendorProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_vendor_products', function (Blueprint $table) {
            $table->foreign(['order_id'], 'order_products_order_id_foreign')->references(['id'])->on('orders')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['product_id'], 'order_products_product_id_foreign')->references(['id'])->on('products')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['tax_category_id'], 'order_products_tax_category_id_foreign')->references(['id'])->on('tax_categories')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['variant_id'], 'order_products_variant_id_foreign')->references(['id'])->on('product_variants')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['vendor_id'], 'order_products_vendor_id_foreign')->references(['id'])->on('vendors')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['category_id'])->references(['id'])->on('categories')->onUpdate('NO ACTION')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_vendor_products', function (Blueprint $table) {
            $table->dropForeign('order_products_order_id_foreign');
            $table->dropForeign('order_products_product_id_foreign');
            $table->dropForeign('order_products_tax_category_id_foreign');
            $table->dropForeign('order_products_variant_id_foreign');
            $table->dropForeign('order_products_vendor_id_foreign');
            $table->dropForeign('order_vendor_products_category_id_foreign');
        });
    }
}
