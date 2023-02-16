<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sku', 100)->index();
            $table->unsignedBigInteger('product_id')->nullable()->index('product_variants_product_id_foreign');
            $table->string('title')->nullable();
            $table->integer('quantity')->default(0)->index();
            $table->decimal('price', 10)->nullable()->index();
            $table->tinyInteger('position')->default(1);
            $table->decimal('compare_at_price', 10)->nullable()->index();
            $table->string('barcode', 20)->unique();
            $table->decimal('cost_price', 10)->nullable()->index();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->unsignedBigInteger('tax_category_id')->nullable()->index('product_variants_tax_category_id_foreign');
            $table->string('inventory_policy')->nullable();
            $table->string('fulfillment_service')->nullable();
            $table->string('inventory_management')->nullable();
            $table->timestamps();
            $table->tinyInteger('status')->default(1)->comment('1 for avtive, 0 for inactive');

            $table->unique(['sku']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_variants');
    }
}
