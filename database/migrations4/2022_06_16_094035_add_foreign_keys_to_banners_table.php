<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->foreign(['redirect_category_id'])->references(['id'])->on('categories')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->foreign(['redirect_vendor_id'])->references(['id'])->on('vendors')->onUpdate('CASCADE')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropForeign('banners_redirect_category_id_foreign');
            $table->dropForeign('banners_redirect_vendor_id_foreign');
        });
    }
}
