<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('icon', 150)->nullable();
            $table->string('slug', 30)->unique();
            $table->unsignedBigInteger('type_id')->nullable();
            $table->string('image', 150)->nullable();
            $table->tinyInteger('is_visible')->nullable();
            $table->tinyInteger('status')->default(1)->comment('0 - pending, 1 - active, 2 - blocked');
            $table->smallInteger('position')->default(1)->comment('for same position, display asc order');
            $table->tinyInteger('is_core')->default(1)->comment('0 - no, 1 - yes');
            $table->tinyInteger('can_add_products')->default(0)->comment('0 - no, 1 - yes');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->string('client_code', 10)->nullable()->index('categories_client_code_foreign');
            $table->string('display_mode', 255)->nullable()->comment('only products name, product with description');
            $table->bigInteger('warning_page_id')->nullable();
            $table->bigInteger('template_type_id')->nullable();
            $table->longText('warning_page_design')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->tinyInteger('show_wishlist')->default(1)->comment('1 for yes, 0 for no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
