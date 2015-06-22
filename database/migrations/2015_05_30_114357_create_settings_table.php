<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 30);
            $table->string('type', 30);
            $table->text('value');
            $table->unique(['code', 'type']);
        });

        // Insert system data
        DB::table('settings')->insert([
            ['code' => 'email', 'type' => 'noreply', 'value' => 'noreply@domain.com'],
            ['code' => 'name', 'type' => 'noreply', 'value' => 'Web App X'],
            ['code' => 'footer', 'type' => 'email', 'value' => "<p>&nbsp;<p>\r\n<p>Best regards,</p>"],
            ['code' => 'header', 'type' => 'email', 'value' => 'Dear {email},<br/><br/><br/>'],
            ['code' => 'facebook', 'type' => 'socmed_url', 'value' => 'https://www.facebook.com/webarq'],
            ['code' => 'twitter', 'type' => 'socmed_url', 'value' => 'https://twitter.com/webarq'],
            ['code' => 'youtube', 'type' => 'socmed_url', 'value' => 'http://www.youtube.com/webarq'],
        ]);
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
