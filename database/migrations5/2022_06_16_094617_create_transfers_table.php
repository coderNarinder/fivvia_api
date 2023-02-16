<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('from_type');
            $table->unsignedBigInteger('from_id');
            $table->string('to_type');
            $table->unsignedBigInteger('to_id');
            $table->enum('status', ['exchange', 'transfer', 'paid', 'refund', 'gift'])->default('transfer');
            $table->enum('status_last', ['exchange', 'transfer', 'paid', 'refund', 'gift'])->nullable();
            $table->unsignedBigInteger('deposit_id')->index('transfers_deposit_id_foreign');
            $table->unsignedBigInteger('withdraw_id')->index('transfers_withdraw_id_foreign');
            $table->decimal('discount', 64, 0)->default(0);
            $table->decimal('fee', 64, 0)->default(0);
            $table->char('uuid', 36)->unique();
            $table->timestamps();

            $table->index(['from_type', 'from_id']);
            $table->index(['to_type', 'to_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transfers');
    }
}
