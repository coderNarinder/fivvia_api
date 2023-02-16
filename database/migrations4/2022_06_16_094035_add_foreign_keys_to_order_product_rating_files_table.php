<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToOrderProductRatingFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_product_rating_files', function (Blueprint $table) {
            $table->foreign(['order_product_rating_id'])->references(['id'])->on('order_product_ratings')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_product_rating_files', function (Blueprint $table) {
            $table->dropForeign('order_product_rating_files_order_product_rating_id_foreign');
        });
    }
}
