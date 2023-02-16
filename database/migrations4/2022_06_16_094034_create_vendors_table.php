<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100)->index();
            $table->mediumText('slug')->nullable();
            $table->text('desc')->nullable();
            $table->string('logo', 150)->nullable();
            $table->string('banner', 150)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('phone_no')->nullable();
            $table->decimal('latitude', 15, 12)->nullable();
            $table->decimal('longitude', 16, 12)->nullable();
            $table->decimal('order_min_amount', 10)->default(0)->index();
            $table->string('order_pre_time', 40)->nullable()->index();
            $table->string('auto_reject_time', 40)->nullable()->index();
            $table->decimal('commission_percent', 10)->nullable()->default(1)->index();
            $table->decimal('commission_fixed_per_order', 10)->nullable()->default(0)->index();
            $table->decimal('commission_monthly', 10)->nullable()->default(0)->index();
            $table->tinyInteger('dine_in')->default(0)->index()->comment('1 for yes, 0 for no');
            $table->tinyInteger('takeaway')->default(0)->index()->comment('1 for yes, 0 for no');
            $table->tinyInteger('delivery')->default(0)->index()->comment('1 for yes, 0 for no');
            $table->tinyInteger('status')->default(1)->comment('1-active, 0-pending, 2-blocked');
            $table->tinyInteger('add_category')->default(1)->index()->comment('0 for no, 1 for yes');
            $table->tinyInteger('setting')->default(0)->comment('0 for no, 1 for yes');
            $table->tinyInteger('is_show_vendor_details')->default(0);
            $table->timestamps();
            $table->tinyInteger('show_slot')->default(1)->comment('1 for yes, 0 for no');
            $table->unsignedBigInteger('vendor_templete_id')->nullable()->index('vendors_vendor_templete_id_foreign');
            $table->tinyInteger('auto_accept_order')->default(0)->comment('1 for yes, 0 for no');
            $table->decimal('service_fee_percent', 10)->nullable()->default(0);
            $table->integer('slot_minutes')->nullable();
            $table->decimal('order_amount_for_delivery_fee', 64, 0)->default(0);
            $table->decimal('delivery_fee_minimum', 64, 0)->default(0);
            $table->decimal('delivery_fee_maximum', 64, 0)->default(0);
            $table->tinyInteger('closed_store_order_scheduled')->default(0);
            $table->integer('pincode')->nullable();
            $table->string('shiprocket_pickup_name')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendors');
    }
}
