<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionPlansVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_plans_vendor', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->text('slug')->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->unsignedDecimal('price', 12)->default(0);
            $table->unsignedInteger('period')->default(0)->comment('plan validity in days');
            $table->string('frequency');
            $table->smallInteger('sort_order')->default(1)->comment('for same position, display asc order');
            $table->enum('status', ['0', '1'])->default('1')->comment('0=Inactive, 1=Active');
            $table->unsignedTinyInteger('on_request')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_plans_vendor');
    }
}
