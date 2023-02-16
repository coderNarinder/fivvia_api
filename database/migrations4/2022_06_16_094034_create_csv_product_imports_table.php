<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCsvProductImportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('csv_product_imports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vendor_id')->nullable()->index('csv_product_imports_vendor_id_foreign');
            $table->string('name')->nullable();
            $table->string('path')->nullable();
            $table->unsignedBigInteger('uploaded_by')->nullable()->index('csv_product_imports_uploaded_by_foreign');
            $table->tinyInteger('type')->nullable()->default(0)->comment('0 for csv, 1 for woocommerce');
            $table->tinyInteger('status')->nullable()->comment('1-Pending, 2-Success, 3-Failed, 4-In-progress');
            $table->string('raw_data')->nullable();
            $table->longText('error')->nullable();
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
        Schema::dropIfExists('csv_product_imports');
    }
}
