<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToProductVariantImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_variant_images', function (Blueprint $table) {
            $table->foreign(['product_image_id'])->references(['id'])->on('product_images')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['product_variant_id'])->references(['id'])->on('product_variants')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_variant_images', function (Blueprint $table) {
            $table->dropForeign('product_variant_images_product_image_id_foreign');
            $table->dropForeign('product_variant_images_product_variant_id_foreign');
        });
    }
}
