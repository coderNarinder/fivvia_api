<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('team_id')->nullable()->index('agents_team_id_foreign');
            $table->string('name', 50);
            $table->string('profile_picture', 150);
            $table->string('type', 20)->comment('Freelancer,In house');
            $table->unsignedBigInteger('vehicle_type_id')->nullable()->index('agents_vehicle_type_id_foreign');
            $table->string('make_model', 60)->nullable();
            $table->string('plate_number', 15)->nullable();
            $table->string('phone_number', 24)->nullable()->index();
            $table->string('color', 15)->nullable();
            $table->tinyInteger('is_activated')->default(0)->index()->comment('1 for yes, 0 for no');
            $table->tinyInteger('is_available')->default(0)->index()->comment('1 for yes, 0 for no');
            $table->tinyInteger('is_approved')->default(1)->comment('1 for yes, 0 for no');
            $table->string('device_type', 15)->nullable();
            $table->string('device_token')->nullable();
            $table->string('access_token')->nullable()->index();
            $table->timestamps();
            $table->decimal('cash_at_hand', 6)->nullable()->default(0);
            $table->string('uid', 20)->nullable();
            $table->integer('customer_type_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agents');
    }
}
