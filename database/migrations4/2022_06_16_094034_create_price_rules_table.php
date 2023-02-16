<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_rules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 50)->index();
            $table->dateTime('start_date_time')->index();
            $table->dateTime('end_date_time')->index();
            $table->tinyInteger('is_default')->nullable()->index()->comment('1 yes, 0 no');
            $table->unsignedBigInteger('geo_id')->nullable()->index('price_rules_geo_id_foreign');
            $table->unsignedBigInteger('team_id')->nullable()->index('price_rules_team_id_foreign');
            $table->unsignedBigInteger('team_tag_id')->nullable()->index('price_rules_team_tag_id_foreign');
            $table->unsignedBigInteger('driver_tag_id')->nullable()->index('price_rules_driver_tag_id_foreign');
            $table->decimal('base_price', 6)->nullable()->index();
            $table->string('base_duration', 15)->nullable()->index();
            $table->decimal('base_distance', 6, 1)->nullable()->index();
            $table->string('base_waiting', 15)->nullable()->index();
            $table->decimal('duration_price', 4)->nullable()->index();
            $table->decimal('waiting_price', 4)->nullable()->index();
            $table->decimal('distance_fee', 4)->nullable()->index();
            $table->decimal('cancel_fee', 4)->nullable()->index();
            $table->smallInteger('agent_commission_percentage')->nullable()->index();
            $table->smallInteger('agent_commission_fixed')->nullable()->index();
            $table->smallInteger('freelancer_commission_percentage')->nullable()->index();
            $table->smallInteger('freelancer_commission_fixed')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('price_rules');
    }
}
