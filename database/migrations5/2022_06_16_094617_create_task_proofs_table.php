<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskProofsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_proofs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('image')->default(0)->comment('1 for enable 0 for disable');
            $table->tinyInteger('image_requried')->default(0)->comment('1 for requried 0 for not requried');
            $table->tinyInteger('signature')->default(0)->comment('1 for enable 0 for disable');
            $table->tinyInteger('signature_requried')->default(0)->comment('1 for requried 0 for not requried');
            $table->tinyInteger('note')->default(0)->comment('1 for enable 0 for disable');
            $table->tinyInteger('note_requried')->default(0)->comment('1 for requried 0 for not requried');
            $table->timestamps();
            $table->tinyInteger('type')->default(1)->comment('1 for pickup, 2 for drop, 3 for appointment');
            $table->tinyInteger('barcode')->default(1)->comment('1 for enable, 2 for disable');
            $table->tinyInteger('barcode_requried')->default(1)->comment('1 for requried, 2 for Not requried');
            $table->tinyInteger('otp')->default(0);
            $table->tinyInteger('otp_requried')->default(0);
            $table->tinyInteger('face')->default(0);
            $table->tinyInteger('face_requried')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_proofs');
    }
}
