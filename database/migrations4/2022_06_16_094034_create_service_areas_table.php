<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_areas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 60)->index();
            $table->text('description')->nullable();
            $table->text('geo_array')->nullable();
            $table->smallInteger('zoom_level')->default(13);
            $table->geometry('polygon')->nullable();
            $table->unsignedBigInteger('vendor_id')->nullable()->index('service_areas_vendor_id_foreign');
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
        Schema::dropIfExists('service_areas');
    }
}
