<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function($table) {
            $table->increments('id');
            $table->text('title');
            $table->text('content');
            $table->string('image_file_name')->nullable();
            $table->integer('image_file_size')->nullable();
            $table->string('image_content_type')->nullable();
            $table->timestamp('image_updated_at')->nullable();
            $table->integer('view_count')->nullable()->default(0);
            $table->integer('like_count')->nullable()->default(0);
            $table->integer('comment_count')->nullable()->default(0);
            $table->integer('rating_count')->nullable()->default(0);
            $table->decimal('rating_average', 2, 1)->nullable()->default(0);

            $table->softDeletes();
            $table->timestamps();
            $table->integer('location')->nullable();
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
        Schema::drop('posts');
    }
}
