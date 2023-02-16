<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRefferalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_refferals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('refferal_code', 15)->nullable();
            $table->unsignedBigInteger('reffered_by')->nullable();
            $table->unsignedBigInteger('user_id')->nullable()->index('user_refferals_user_id_foreign');
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
        Schema::dropIfExists('user_refferals');
    }
}
