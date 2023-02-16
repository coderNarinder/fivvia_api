<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSubClientPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_client_payments', function (Blueprint $table) {
            $table->foreign(['sub_client_id'])->references(['id'])->on('sub_clients')->onUpdate('CASCADE')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_client_payments', function (Blueprint $table) {
            $table->dropForeign('sub_client_payments_sub_client_id_foreign');
        });
    }
}
