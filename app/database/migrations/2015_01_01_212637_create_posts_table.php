<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function ($table) {
            $table->increments('id');
            $table->integer('legacy_id');
            $table->enum('source', ['drupal', 'ysupload']);
            $table->string('poster_username')->nullable();
            $table->string('post_subject');
            $table->longText('post_text');
            $table->integer('forum_id');
            $table->integer('topic_id')->nullable();
            $table->timestamp('posted_on')->nullable();
            $table->timestamps();
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
