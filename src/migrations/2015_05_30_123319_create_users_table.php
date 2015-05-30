<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function($table)
		{
			$table->increments('id');
			$table->string('email', 254)->unique();
			$table->string('first_name', 16);
			$table->string('last_name', 16);
			$table->string('password', 60);
			$table->string('remember_token', 60)->nullable()->default(null);
			$table->timestamps();
		});

		DB::table('users')->insert([
			['id' => 2, 'email' => 'qosdil@webarq.com', 'first_name' => 'Stewie', 'last_name' => 'Griffin', 'password' => '$2y$08$hb3/kWLXQ3JfR6dOUbrkk.Bm0Wy6sElIhYp89o0OgxoA3ORLObwSa'],
		]);
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
