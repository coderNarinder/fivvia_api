<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreign(['brand_id'])->references(['id'])->on('brands')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['category_id'])->references(['id'])->on('categories')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['country_origin_id'])->references(['id'])->on('countries')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['tax_category_id'])->references(['id'])->on('tax_categories')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['type_id'])->references(['id'])->on('types')->onUpdate('NO ACTION')->onDelete('SET NULL');
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
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('products_brand_id_foreign');
            $table->dropForeign('products_category_id_foreign');
            $table->dropForeign('products_country_origin_id_foreign');
            $table->dropForeign('products_tax_category_id_foreign');
            $table->dropForeign('products_type_id_foreign');
            $table->dropForeign('products_vendor_id_foreign');
        });
    }
}
