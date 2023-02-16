<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_vendors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id')->nullable()->index('order_vendors_order_id_foreign');
            $table->unsignedBigInteger('vendor_id')->nullable()->index('order_vendors_vendor_id_foreign');
            $table->unsignedBigInteger('vendor_dinein_table_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->unsignedDecimal('delivery_fee')->nullable();
            $table->tinyInteger('status')->comment('0-Created, 1-Confirmed, 2-Dispatched, 3-Delivered');
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->string('coupon_code')->nullable();
            $table->decimal('taxable_amount', 10)->nullable();
            $table->decimal('subtotal_amount', 10)->nullable();
            $table->decimal('payable_amount')->nullable();
            $table->decimal('discount_amount')->nullable();
            $table->string('web_hook_code', 120)->nullable();
            $table->decimal('admin_commission_percentage_amount', 10)->nullable();
            $table->decimal('admin_commission_fixed_amount', 10)->nullable();
            $table->tinyInteger('coupon_paid_by')->nullable()->default(1)->comment('0-Vendor, 1-Admin');
            $table->tinyInteger('payment_option_id')->nullable();
            $table->unsignedTinyInteger('dispatcher_status_option_id')->nullable();
            $table->unsignedTinyInteger('order_status_option_id')->nullable();
            $table->timestamps();
            $table->string('dispatch_traking_url', 500)->nullable();
            $table->unsignedInteger('order_pre_time')->nullable()->default(0);
            $table->unsignedInteger('user_to_vendor_time')->nullable()->default(0);
            $table->mediumText('reject_reason')->nullable();
            $table->decimal('service_fee_percentage_amount', 10)->nullable()->default(0);
            $table->unsignedBigInteger('cancelled_by')->nullable();
            $table->string('lalamove_tracking_url')->nullable();
            $table->enum('shipping_delivery_type', ['D', 'L', 'S', 'SR'])->default('D');
            $table->integer('courier_id')->nullable();
            $table->string('ship_order_id')->nullable();
            $table->string('ship_shipment_id')->nullable();
            $table->string('ship_awb_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_vendors');
    }
}
