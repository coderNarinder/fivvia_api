<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPricingColumnsToPriceRules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('price_rules', function (Blueprint $table) {
            $table->string('name')->nullable(true)->change();
            $table->datetime('start_date_time')->nullable(true)->change();
            $table->datetime('end_date_time')->nullable(true)->change();
            $table->decimal('duration_price', 10, 2)->nullable(true)->default(0)->change();
            $table->decimal('waiting_price', 10, 2)->nullable(true)->default(0)->change();
            $table->decimal('distance_fee', 10, 2)->nullable(true)->default(0)->change();
            $table->decimal('cancel_fee', 10, 2)->nullable(true)->default(0)->change();
            $table->string('slug', 255)->nullable();
            $table->string('business_unit', 255)->nullable();
            $table->unsignedBigInteger('tags_for_agent_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('geofence_type')->nullable()->comment('1 = City-based, 2 = Sub-Geofence');
            $table->decimal('minimum_fare', 10, 2)->nullable()->default(0);
            $table->decimal('scheduled_order_extra', 10, 2)->nullable()->default(0);
            $table->tinyInteger('status')->default(0)->comments('0=Inactive, 1=Active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('price_rules', function (Blueprint $table) {
            $table->DropColumn('slug');
            $table->DropColumn('business_unit');
            $table->DropColumn('service_type_id');
            $table->DropColumn('city_id');
            $table->DropColumn('geofence_type');
            $table->DropColumn('minimum_fare');
            $table->DropColumn('scheduled_order_extra');
            $table->DropColumn('status');
        });
    }
}
