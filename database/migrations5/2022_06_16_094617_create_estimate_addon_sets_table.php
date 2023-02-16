<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstimateAddonSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estimate_addon_sets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 100)->nullable();
            $table->tinyInteger('min_select')->default(1);
            $table->tinyInteger('max_select')->default(1);
            $table->smallInteger('position')->default(1);
            $table->tinyInteger('status')->default(1)->comment('0 - pending, 1 - active, 2 - blocked');
            $table->tinyInteger('is_core')->default(1)->comment('0 - no, 1 - yes');
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
        Schema::dropIfExists('estimate_addon_sets');
    }
}
