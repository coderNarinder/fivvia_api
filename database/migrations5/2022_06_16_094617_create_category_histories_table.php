<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_id')->index('category_histories_category_id_foreign');
            $table->string('action', 20)->default('add')->comment('add, update, delete, block, active');
            $table->string('updater_role', 255)->default('SuperAdmin')->comment('SuperAdmin, Admin, Seller');
            $table->unsignedBigInteger('update_id')->nullable();
            $table->unsignedBigInteger('client_code')->nullable();
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
        Schema::dropIfExists('category_histories');
    }
}
