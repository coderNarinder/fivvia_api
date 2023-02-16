<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('created_by')->nullable();
            $table->string('order_number')->nullable();
            $table->tinyInteger('payment_option_id')->default(1);
            $table->unsignedBigInteger('customer_id')->nullable()->index('orders_customer_id_foreign');
            $table->unsignedBigInteger('driver_id')->nullable()->index('orders_driver_id_foreign');
            $table->dateTime('scheduled_date_time')->nullable()->index();
            $table->text('key_value_set')->nullable();
            $table->string('conditions_tag_team', 100)->nullable()->index();
            $table->string('conditions_tag_driver', 100)->nullable()->index();
            $table->string('recipient_phone', 15)->nullable()->index();
            $table->text('task_description')->nullable();
            $table->string('images_array')->nullable();
            $table->string('auto_alloction', 100)->nullable();
            $table->dateTime('order_time')->nullable()->index();
            $table->string('order_type', 20)->nullable()->index();
            $table->timestamps();
            $table->text('note')->nullable();
            $table->decimal('cash_to_be_collected', 10)->nullable();
            $table->decimal('base_price', 6)->nullable();
            $table->string('base_duration', 15)->nullable();
            $table->decimal('base_distance', 4, 3)->nullable();
            $table->string('base_waiting', 15)->nullable();
            $table->decimal('duration_price', 4)->nullable();
            $table->decimal('waiting_price', 4)->nullable();
            $table->decimal('distance_fee', 4)->nullable();
            $table->decimal('cancel_fee', 4)->nullable();
            $table->smallInteger('agent_commission_percentage')->nullable();
            $table->smallInteger('agent_commission_fixed')->nullable();
            $table->smallInteger('freelancer_commission_percentage')->nullable();
            $table->smallInteger('freelancer_commission_fixed')->nullable();
            $table->decimal('actual_time', 6)->nullable();
            $table->decimal('actual_distance', 6)->nullable();
            $table->decimal('order_cost', 6)->nullable();
            $table->decimal('driver_cost', 6)->nullable();
            $table->string('proof_image')->nullable();
            $table->string('proof_signature')->nullable();
            $table->string('unique_id', 20)->nullable();
            $table->integer('net_quantity')->nullable();
            $table->string('call_back_url', 500)->nullable();
            $table->string('completion_otp', 100)->nullable();
            $table->tinyInteger('payment_method')->default(1)->comment('1 - Credit Card, 2 - Cash On Delivery, 3 - Paypal, 4 - Wallet');
            $table->integer('currency_id')->nullable();
            $table->unsignedInteger('loyalty_membership_id')->nullable();
            $table->unsignedInteger('luxury_option_id')->nullable();
            $table->decimal('loyalty_points_used', 10)->nullable();
            $table->decimal('loyalty_amount_saved', 10)->nullable();
            $table->decimal('loyalty_points_earned', 10)->nullable();
            $table->integer('total_discount')->nullable();
            $table->decimal('total_delivery_fee')->nullable();
            $table->integer('total_amount')->default(0);
            $table->unsignedDecimal('wallet_amount_used', 12)->default(0);
            $table->decimal('subscription_discount', 12)->nullable();
            $table->integer('taxable_amount')->default(0);
            $table->unsignedDecimal('tip_amount')->nullable()->default(0);
            $table->tinyInteger('payment_status')->nullable()->default(0)->comment('0=Pending, 1=Paid');
            $table->mediumText('comment_for_pickup_driver')->nullable();
            $table->mediumText('comment_for_dropoff_driver')->nullable();
            $table->mediumText('comment_for_vendor')->nullable();
            $table->dateTime('schedule_pickup')->nullable();
            $table->dateTime('schedule_dropoff')->nullable();
            $table->text('specific_instructions')->nullable();
            $table->tinyInteger('is_gift')->nullable()->default(0)->comment('0-No, 1-Yes');
            $table->decimal('total_service_fee', 10)->nullable()->default(0);
            $table->enum('shipping_delivery_type', ['D', 'L', 'S', 'SR'])->default('D');
            $table->string('scheduled_slot')->nullable();
            $table->integer('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
