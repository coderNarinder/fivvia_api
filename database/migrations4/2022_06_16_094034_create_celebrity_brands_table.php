<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCelebrityBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('celebrity_brands', function (Blueprint $table) {
            $table->unsignedBigInteger('celebrity_id')->nullable()->index('celebrity_brands_celebrity_id_foreign');
            $table->unsignedBigInteger('brand_id')->nullable()->index('celebrity_brands_brand_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('celebrity_brands');
    }
}
