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
            $table->string('name', 16);
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->string('kana', 16);
            $table->string('telephone_no');
            $table->date('birthday');
            $table->string('note', 300);
            $table->integer('role');
            $table->integer('boss_id')->unsigned();
            $table->boolean('disabled')->default(false);
            $table->rememberToken();
            $table->timestamps();
            $table->index(['name', 'email', 'kana', 'telephone_no', 'birthday', 'role']);
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
