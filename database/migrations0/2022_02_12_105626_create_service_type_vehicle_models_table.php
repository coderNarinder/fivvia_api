<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceTypeVehicleModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_type_vehicle_models', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tags_for_agent_id')->nullable();
            $table->unsignedBigInteger('vehicle_model_id')->nullable();
            $table->timestamps();

            $table->foreign('tags_for_agent_id')->references('id')->on('tags_for_agents')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('vehicle_model_id')->references('id')->on('vehicle_models')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_type_vehicle_models');
    }
}
