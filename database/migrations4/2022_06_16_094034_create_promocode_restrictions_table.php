<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromocodeRestrictionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promocode_restrictions', function (Blueprint $table) {
            $table->unsignedBigInteger('promocode_id')->nullable()->index('promocode_restrictions_promocode_id_foreign');
            $table->tinyInteger('restriction_type')->nullable()->default(0)->comment('0- product, 1-vendor, 2-category');
            $table->unsignedBigInteger('data_id')->nullable();
            $table->tinyInteger('is_included')->default(1)->index()->comment('1 for yes, 0 for no');
            $table->tinyInteger('is_excluded')->default(1)->index()->comment('1 for yes, 0 for no');
            $table->timestamps();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promocode_restrictions');
    }
}
