<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateReallocateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reallocate_orders', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 255)->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->string('business_unit', 255)->nullable();
            $table->unsignedBigInteger('service_type_id')->nullable();
            $table->tinyInteger('status')->default(0)->comments('0=Inactive, 1=Active');
            $table->unsignedBigInteger('geofence_type')->nullable()->comment('1 = City-based, 2 = Sub-Geofence');
            $table->bigInteger('geo_id')->unsigned()->nullable();
            $table->unsignedBigInteger("updated_by_id")->default(null)->nullable();
            $table->timestamps();
        });

        Schema::create('reallocate_order_rings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reallocate_order_id')->nullable();
            $table->integer('distance_from')->nullable()->default(0)->comment("in km");
            $table->integer('distance_to')->nullable()->default(0)->comment("in km");
            $table->integer('wait_time')->nullable()->default(0)->comment("in sec");
            $table->timestamps();

            $table->foreign('reallocate_order_id')->references('id')->on('reallocate_orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('reallocate_orders');
        Schema::dropIfExists('reallocate_order_rings');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
