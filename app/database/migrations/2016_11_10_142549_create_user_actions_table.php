<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserActionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('user_actions', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('user_id');
            $table->tinyInteger('action_id');
            $table->string('action_value')->nullable();
            $table->integer('target_id');
            $table->tinyInteger('target_type_id');

            $table->softDeletes();
            $table->timestamps();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_actions');
	}

}
