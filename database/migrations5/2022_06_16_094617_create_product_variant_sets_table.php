<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariantSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variant_sets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id')->nullable()->index('product_variant_sets_product_id_foreign');
            $table->unsignedBigInteger('product_variant_id')->nullable()->index('product_variant_sets_product_variant_id_foreign');
            $table->unsignedBigInteger('variant_type_id')->nullable()->index('product_variant_sets_variant_type_id_foreign');
            $table->unsignedBigInteger('variant_option_id')->nullable()->index('product_variant_sets_variant_option_id_foreign');
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
        Schema::dropIfExists('product_variant_sets');
    }
}
