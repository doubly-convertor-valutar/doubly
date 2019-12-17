<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCurrencies extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('currencies',function(Blueprint $table){

			$table->increments('id');
            $table->integer('user_id')->unsigned()->length(11);
			$table->string('currency',20);
			$table->integer('position');

            $table->foreign('user_id')->references('id')->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('currencies');
	}

}
