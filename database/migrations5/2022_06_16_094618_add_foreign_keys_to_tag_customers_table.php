<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTagCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tag_customers', function (Blueprint $table) {
            $table->foreign(['customer_id'])->references(['id'])->on('customers')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['tag_id'])->references(['id'])->on('tags')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tag_customers', function (Blueprint $table) {
            $table->dropForeign('tag_customers_customer_id_foreign');
            $table->dropForeign('tag_customers_tag_id_foreign');
        });
    }
}
