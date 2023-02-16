<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('latitude', 10, 8)->default(0);
            $table->decimal('longitude', 12, 8)->default(0);
            $table->string('short_name', 50)->nullable()->index();
            $table->string('address', 100)->nullable();
            $table->integer('post_code')->nullable()->index();
            $table->unsignedBigInteger('customer_id')->nullable()->index('locations_customer_id_foreign');
            $table->timestamps();
            $table->string('phone_number', 24)->nullable();
            $table->string('email', 70)->nullable();
            $table->integer('location_status')->default(1)->comment('1 for active, 0 deleted');
            $table->time('due_after')->default('00:00:00');
            $table->time('due_before')->default('00:00:00');
            $table->string('flat_no', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations');
    }
}
