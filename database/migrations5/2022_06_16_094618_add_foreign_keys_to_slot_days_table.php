<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSlotDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('slot_days', function (Blueprint $table) {
            $table->foreign(['slot_id'])->references(['id'])->on('vendor_slots')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('slot_days', function (Blueprint $table) {
            $table->dropForeign('slot_days_slot_id_foreign');
        });
    }
}
