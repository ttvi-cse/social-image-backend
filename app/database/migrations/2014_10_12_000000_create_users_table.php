<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
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
        Schema::create('users', function($table) {
            $table->increments('id');
            $table->string('username', 60);
            $table->string('password');
            $table->string('email');
            $table->string('first_name', 30)->nullable();
            $table->string('last_name', 30)->nullable();
            $table->tinyInteger('gender')->nullable();

            $table->string('avatar_file_name')->nullable();
            $table->integer('avatar_file_size')->nullable();
            $table->string('avatar_content_type')->nullable();
            $table->timestamp('avatar_updated_at')->nullable();

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
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
