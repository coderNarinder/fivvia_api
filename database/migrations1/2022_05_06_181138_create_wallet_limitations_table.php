<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletLimitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_limitations', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 255)->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('tags_for_agent_id')->nullable();
            $table->string('business_unit', 255)->nullable();
            $table->decimal('soft_limit', 12, 2)->nullable();
            $table->decimal('hard_limit', 12, 2)->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->tinyInteger('status')->nullable()->default(0)->comment('0=Inactive, 1=Active');
            $table->timestamps();

            // $table->foreign('city_id')->references('id')->on('cities')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('tags_for_agent_id')->references('id')->on('tags_for_agents')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallet_limitations');
    }
}
