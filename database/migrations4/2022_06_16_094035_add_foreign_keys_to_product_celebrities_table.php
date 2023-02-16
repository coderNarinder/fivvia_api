<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToProductCelebritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_celebrities', function (Blueprint $table) {
            $table->foreign(['celebrity_id'])->references(['id'])->on('celebrities')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_celebrities', function (Blueprint $table) {
            $table->dropForeign('product_celebrities_celebrity_id_foreign');
            $table->dropForeign('product_celebrities_product_id_foreign');
        });
    }
}
