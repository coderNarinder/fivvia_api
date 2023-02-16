<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_media', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('media_type')->default(1)->index()->comment('1 - image, 2 - video, 3 - file');
            $table->unsignedBigInteger('vendor_id')->nullable()->index('vendor_media_vendor_id_foreign');
            $table->string('path', 255)->nullable();
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
        Schema::dropIfExists('vendor_media');
    }
}
