<?php

use Illuminate\Support\Facades\Schema;
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
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('legacy_id');
            $table->integer('phpbb_id')->nullable()->default(null);
            $table->enum('source', ['drupal', 'ysupload']);
            $table->enum('type', ['addon', 'screenshot', 'video', 'story']);
            $table->string('username')->nullable()->default(null);
            $table->string('subject');
            $table->longText('body');
            $table->integer('forum_id');
            $table->integer('topic_id')->nullable()->default(null);
            $table->timestamp('posted_on')->nullable()->default(null);
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
