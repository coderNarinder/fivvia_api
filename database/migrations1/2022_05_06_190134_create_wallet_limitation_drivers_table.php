<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletLimitationDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_limitation_drivers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wallet_limitation_id')->nullable();
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->timestamps();

            $table->foreign('wallet_limitation_id')->references('id')->on('wallet_limitations')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('driver_id')->references('id')->on('agents')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallet_limitation_drivers');
    }
}
