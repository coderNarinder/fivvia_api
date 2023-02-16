<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCelebritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_celebrities', function (Blueprint $table) {
            $table->unsignedBigInteger('celebrity_id')->nullable()->index('product_celebrities_celebrity_id_foreign');
            $table->unsignedBigInteger('product_id')->nullable()->index('product_celebrities_product_id_foreign');
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
        Schema::dropIfExists('product_celebrities');
    }
}
