<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSettings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('settings',function(Blueprint $table){
				$table->increments('id');
            	$table->integer('user_id')->unsigned()->length(11);
				$table->string('key');
				$table->string('value');

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
		Schema::drop('settings');
	}

}
