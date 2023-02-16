<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorDineinTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_dinein_tables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('table_number')->nullable();
            $table->string('image')->nullable();
            $table->unsignedBigInteger('vendor_id')->nullable()->index('vendor_dinein_tables_vendor_id_foreign');
            $table->integer('seating_number')->nullable();
            $table->unsignedBigInteger('vendor_dinein_category_id')->nullable()->index('vendor_dinein_tables_vendor_dinein_category_id_foreign');
            $table->tinyInteger('status')->default(0)->comment('0-active, 1-inactive');
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
        Schema::dropIfExists('vendor_dinein_tables');
    }
}
