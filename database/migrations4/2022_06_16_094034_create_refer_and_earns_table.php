<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferAndEarnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refer_and_earns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('reffered_by_amount')->nullable();
            $table->decimal('reffered_to_amount')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable()->index('refer_and_earns_updated_by_foreign');
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
        Schema::dropIfExists('refer_and_earns');
    }
}
