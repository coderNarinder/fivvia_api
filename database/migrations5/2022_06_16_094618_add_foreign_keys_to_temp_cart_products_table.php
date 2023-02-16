<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTempCartProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('temp_cart_products', function (Blueprint $table) {
            $table->foreign(['cart_id'])->references(['id'])->on('temp_carts')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['created_by'])->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['tax_category_id'])->references(['id'])->on('tax_categories')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['tax_rate_id'])->references(['id'])->on('tax_rates')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['variant_id'])->references(['id'])->on('product_variants')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['vendor_id'])->references(['id'])->on('vendors')->onUpdate('NO ACTION')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('temp_cart_products', function (Blueprint $table) {
            $table->dropForeign('temp_cart_products_cart_id_foreign');
            $table->dropForeign('temp_cart_products_created_by_foreign');
            $table->dropForeign('temp_cart_products_product_id_foreign');
            $table->dropForeign('temp_cart_products_tax_category_id_foreign');
            $table->dropForeign('temp_cart_products_tax_rate_id_foreign');
            $table->dropForeign('temp_cart_products_variant_id_foreign');
            $table->dropForeign('temp_cart_products_vendor_id_foreign');
        });
    }
}
