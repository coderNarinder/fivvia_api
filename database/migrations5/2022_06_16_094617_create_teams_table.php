<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('manager_id')->nullable()->index('teams_manager_id_foreign');
            $table->string('name', 40)->index();
            $table->smallInteger('location_accuracy')->index();
            $table->smallInteger('location_frequency')->index();
            $table->timestamps();
            $table->string('client_id', 10)->nullable()->index('teams_client_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teams');
    }
}
