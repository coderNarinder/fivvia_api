<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToVendorDineinTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_dinein_tables', function (Blueprint $table) {
            $table->foreign(['vendor_dinein_category_id'])->references(['id'])->on('vendor_dinein_categories')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['vendor_id'])->references(['id'])->on('vendors')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_dinein_tables', function (Blueprint $table) {
            $table->dropForeign('vendor_dinein_tables_vendor_dinein_category_id_foreign');
            $table->dropForeign('vendor_dinein_tables_vendor_id_foreign');
        });
    }
}
