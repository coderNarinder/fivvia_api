<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('unique_identifier')->nullable();
            $table->unsignedBigInteger('user_id')->nullable()->index('carts_user_id_foreign');
            $table->unsignedBigInteger('created_by')->nullable()->index('carts_created_by_foreign');
            $table->enum('status', ['0', '1', '2'])->comment('0-Active, 1-Blocked, 2-Deleted');
            $table->enum('is_gift', ['0', '1'])->comment('0-Yes, 1-No');
            $table->integer('item_count')->nullable();
            $table->unsignedBigInteger('currency_id')->nullable()->index('carts_currency_id_foreign');
            $table->string('schedule_type')->nullable();
            $table->dateTime('scheduled_date_time')->nullable();
            $table->timestamps();
            $table->text('specific_instructions')->nullable();
            $table->mediumText('comment_for_pickup_driver')->nullable();
            $table->mediumText('comment_for_dropoff_driver')->nullable();
            $table->mediumText('comment_for_vendor')->nullable();
            $table->dateTime('schedule_pickup')->nullable();
            $table->dateTime('schedule_dropoff')->nullable();
            $table->string('scheduled_slot')->nullable();
            $table->string('shipping_delivery_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
}
