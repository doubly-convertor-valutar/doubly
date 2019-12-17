<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCurrencyByCountry extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('currency_by_country',function(Blueprint $table){
			$table->increments('id');
            $table->integer('user_id')->unsigned()->length(11);
			$table->string('country_code');
			$table->string('currency');

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
		Schema::drop('currency_by_country');
	}

}
