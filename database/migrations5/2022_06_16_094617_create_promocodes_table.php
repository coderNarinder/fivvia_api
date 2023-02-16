<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromocodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promocodes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->mediumText('title')->nullable();
            $table->mediumText('short_desc')->nullable();
            $table->unsignedDecimal('amount', 12)->nullable();
            $table->timestamp('expiry_date')->nullable();
            $table->unsignedBigInteger('promo_type_id')->nullable()->index('promocodes_promo_type_id_foreign');
            $table->tinyInteger('allow_free_delivery')->nullable()->default(0)->index()->comment('0- No, 1- yes');
            $table->unsignedInteger('minimum_spend')->nullable()->index();
            $table->unsignedInteger('maximum_spend')->nullable()->index();
            $table->tinyInteger('first_order_only')->nullable()->default(0)->index()->comment('0- No, 1- yes');
            $table->integer('limit_per_user')->nullable()->index();
            $table->integer('limit_total')->nullable()->index();
            $table->tinyInteger('paid_by_vendor_admin')->nullable()->index();
            $table->tinyInteger('is_deleted')->nullable()->default(0)->index()->comment('0- No, 1- yes');
            $table->unsignedBigInteger('created_by')->nullable()->index('promocodes_created_by_foreign');
            $table->string('image')->nullable();
            $table->timestamps();
            $table->tinyInteger('restriction_on')->nullable()->default(0)->comment('0- product, 1-vendor');
            $table->tinyInteger('restriction_type')->nullable()->default(0)->comment('0- Include, 1-Exclude');
            $table->unsignedBigInteger('added_by')->nullable();
            $table->string('promo_visibility', 50)->nullable()->default('public')->comment('public/private');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promocodes');
    }
}
