<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Createvehiclemodeltable extends Migration
{
   	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vehicle_models', function(Blueprint $table)
		{
			$table->id();
			$table->string('vehicle_model', 255);
            $table->string('slug', 255);
            $table->decimal('length', 12, 2)->default(0);
            $table->string('refrence_link', 500);
            $table->unsignedBigInteger('vehicle_type_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->decimal('payload', 12, 2)->default(0);
            $table->integer('status')->default(0); 
			$table->timestamps();
		});

        Schema::create('brands', function(Blueprint $table)
		{
			$table->id();
			$table->string('name', 255);
            $table->string('slug', 255);
             $table->integer('status')->default(0); 
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
		Schema::drop('vehicle_models');
        Schema::drop('brands');
	}

}
