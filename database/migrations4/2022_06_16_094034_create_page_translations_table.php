<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->mediumText('title');
            $table->longText('description');
            $table->unsignedBigInteger('page_id')->index('page_translations_page_id_foreign');
            $table->unsignedBigInteger('language_id')->index('page_translations_language_id_foreign');
            $table->mediumText('meta_title')->nullable();
            $table->mediumText('meta_keyword')->nullable();
            $table->mediumText('meta_description')->nullable();
            $table->tinyInteger('is_published')->default(0)->comment('0 draft and 1 for published');
            $table->timestamps();
            $table->tinyInteger('type_of_form')->default(0)->comment('0 for none; 1 for vendor registration; 2 for driver registration;');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('page_translations');
    }
}
