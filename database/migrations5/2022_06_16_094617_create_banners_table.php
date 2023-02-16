<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 150)->index();
            $table->longText('description')->nullable();
            $table->string('image', 150);
            $table->tinyInteger('validity_on')->default(1)->comment('1 - yes, 0 - no');
            $table->tinyInteger('sorting')->default(1);
            $table->tinyInteger('status')->default(1)->index()->comment('1 - active, 0 - pending, 2 - blocked');
            $table->dateTime('start_date_time')->nullable()->index();
            $table->dateTime('end_date_time')->nullable()->index();
            $table->unsignedBigInteger('redirect_category_id')->nullable()->index('banners_redirect_category_id_foreign');
            $table->unsignedBigInteger('redirect_vendor_id')->nullable()->index('banners_redirect_vendor_id_foreign');
            $table->string('link', 255)->nullable();
            $table->timestamps();
            $table->string('image_mobile', 150)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banners');
    }
}
