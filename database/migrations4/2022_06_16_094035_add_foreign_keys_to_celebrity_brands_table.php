<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCelebrityBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('celebrity_brands', function (Blueprint $table) {
            $table->foreign(['brand_id'])->references(['id'])->on('brands')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['celebrity_id'])->references(['id'])->on('celebrities')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('celebrity_brands', function (Blueprint $table) {
            $table->dropForeign('celebrity_brands_brand_id_foreign');
            $table->dropForeign('celebrity_brands_celebrity_id_foreign');
        });
    }
}
