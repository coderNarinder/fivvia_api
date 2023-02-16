<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index('user_addresses_user_id_foreign');
            $table->string('house_number', 100)->nullable();
            $table->string('address')->nullable();
            $table->string('street')->nullable();
            $table->string('city', 60)->nullable();
            $table->string('state', 60)->nullable();
            $table->decimal('latitude', 15, 12)->nullable();
            $table->decimal('longitude', 16, 12)->nullable();
            $table->string('pincode', 60)->nullable();
            $table->tinyInteger('is_primary')->default(0)->comment('1 for yes, 0 for no');
            $table->string('phonecode')->nullable();
            $table->string('country_code')->nullable();
            $table->string('country')->nullable();
            $table->tinyInteger('type')->default(1)->comment('1 - home');
            $table->string('type_name')->nullable();
            $table->text('extra_instruction')->nullable();
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
        Schema::dropIfExists('user_addresses');
    }
}
