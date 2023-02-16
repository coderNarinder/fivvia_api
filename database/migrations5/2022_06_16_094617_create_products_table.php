<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sku', 500);
            $table->string('title', 120)->nullable();
            $table->string('url_slug', 100)->nullable();
            $table->longText('description')->nullable();
            $table->longText('body_html')->nullable();
            $table->unsignedBigInteger('vendor_id')->nullable()->index('products_vendor_id_foreign');
            $table->unsignedBigInteger('category_id')->nullable()->index('products_category_id_foreign');
            $table->unsignedBigInteger('type_id')->nullable()->index('products_type_id_foreign');
            $table->unsignedBigInteger('country_origin_id')->nullable()->index('products_country_origin_id_foreign');
            $table->tinyInteger('is_new')->default(0)->index()->comment('0 - no, 1 - yes');
            $table->tinyInteger('is_featured')->default(0)->index()->comment('0 - no, 1 - yes');
            $table->tinyInteger('is_live')->default(0)->index()->comment('0 - draft, 1 - published, 2 - blocked');
            $table->tinyInteger('is_physical')->default(0)->index()->comment('0 - no, 1 - yes');
            $table->decimal('weight', 10, 4)->nullable();
            $table->string('weight_unit', 10)->nullable();
            $table->tinyInteger('has_inventory')->default(0)->index()->comment('0 - no, 1 - yes');
            $table->tinyInteger('has_variant')->default(0)->comment('0 - no, 1 - yes');
            $table->tinyInteger('sell_when_out_of_stock')->default(0)->index()->comment('0 - no, 1 - yes');
            $table->tinyInteger('requires_shipping')->default(0)->index()->comment('0 - no, 1 - yes');
            $table->tinyInteger('Requires_last_mile')->default(0)->index()->comment('0 - no, 1 - yes');
            $table->decimal('averageRating', 4)->nullable()->index();
            $table->tinyInteger('inquiry_only')->nullable()->default(0);
            $table->dateTime('publish_at')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('brand_id')->nullable()->index('products_brand_id_foreign');
            $table->unsignedBigInteger('tax_category_id')->nullable()->index('products_tax_category_id_foreign');
            $table->softDeletes();
            $table->tinyInteger('pharmacy_check')->nullable()->default(0)->comment('0-No, 1-Yes');
            $table->string('tags')->nullable();
            $table->string('need_price_from_dispatcher')->nullable();
            $table->string('mode_of_service', 50)->nullable();
            $table->integer('delay_order_hrs')->default(0);
            $table->integer('delay_order_min')->default(0);
            $table->integer('pickup_delay_order_hrs')->default(0);
            $table->integer('pickup_delay_order_min')->default(0);
            $table->integer('dropoff_delay_order_hrs')->default(0);
            $table->integer('dropoff_delay_order_min')->default(0);
            $table->integer('need_shipment')->default(0);
            $table->integer('minimum_order_count')->default(1);
            $table->integer('batch_count')->default(1);
            $table->integer('delay_order_hrs_for_dine_in')->default(0);
            $table->integer('delay_order_min_for_dine_in')->default(0);
            $table->integer('delay_order_hrs_for_takeway')->default(0);
            $table->integer('delay_order_min_for_takeway')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
