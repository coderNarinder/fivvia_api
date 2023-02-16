<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToProductVariantSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_variant_sets', function (Blueprint $table) {
            $table->foreign(['product_id'])->references(['id'])->on('products')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['product_variant_id'])->references(['id'])->on('product_variants')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['variant_option_id'])->references(['id'])->on('variant_options')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['variant_type_id'])->references(['id'])->on('variants')->onUpdate('NO ACTION')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_variant_sets', function (Blueprint $table) {
            $table->dropForeign('product_variant_sets_product_id_foreign');
            $table->dropForeign('product_variant_sets_product_variant_id_foreign');
            $table->dropForeign('product_variant_sets_variant_option_id_foreign');
            $table->dropForeign('product_variant_sets_variant_type_id_foreign');
        });
    }
}
