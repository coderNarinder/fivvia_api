<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromoUsagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_usages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('promocode_id')->nullable()->index('promo_usages_promocode_id_foreign');
            $table->unsignedBigInteger('user_id')->nullable()->index('promo_usages_user_id_foreign');
            $table->integer('usage_count')->default(1);
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
        Schema::dropIfExists('promo_usages');
    }
}
