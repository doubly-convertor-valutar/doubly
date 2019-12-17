<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('shop');
            $table->string('token');
            $table->string('plan_name');
            $table->datetime('last_login');
            $table->string('email');
            $table->string('name');
            $table->integer('charge_id')->unsigned()->length(11);
            $table->boolean('is_test')->unsigned();
            $table->datetime('activated_on');
            $table->datetime('last_uninstall');
            $table->integer('trial_days')->unsigned();
            $table->boolean('is_subscribed')->unsigned();
            $table->decimal('price', 10, 2)->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
