<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceRuleDynamicFaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_rule_dynamic_fares', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('price_rule_id')->nullable();
            $table->decimal('distance_fare_from', 10, 2)->nullable()->default(0);
            $table->decimal('distance_fare_to', 10, 2)->nullable()->default(0);
            $table->decimal('distance_fare_value', 10, 2)->nullable()->default(0);
            $table->decimal('duration_fare_from', 10, 2)->nullable()->default(0);
            $table->decimal('duration_fare_to', 10, 2)->nullable()->default(0);
            $table->decimal('duration_fare_value', 10, 2)->nullable()->default(0);
            $table->timestamps();

            $table->foreign('price_rule_id')->references('id')->on('price_rules')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('price_rule_dynamic_fares');
    }
}
