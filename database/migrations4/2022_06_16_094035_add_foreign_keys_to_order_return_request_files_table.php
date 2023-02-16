<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToOrderReturnRequestFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_return_request_files', function (Blueprint $table) {
            $table->foreign(['order_return_request_id'])->references(['id'])->on('order_return_requests')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_return_request_files', function (Blueprint $table) {
            $table->dropForeign('order_return_request_files_order_return_request_id_foreign');
        });
    }
}
