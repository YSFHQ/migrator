<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_id');
            $table->integer('phpbb_attachment_id')->nullable()->default(null); // null until posted as attachment
            // phpBB columns START
            $table->integer('post_msg_id');
            $table->integer('topic_id');
            $table->integer('poster_id');
            $table->string('physical_filename'); // e.g. 123_5b1d21ee26decf67229149f27797c5d8
            $table->string('real_filename'); // e.g. F-16.zip
            $table->integer('download_count');
            $table->string('extension', 10);
            $table->string('mimetype');
            $table->integer('filesize');
            $table->integer('filetime'); // Unix time
            // phpbb columns END

            $table->string('local_path'); // e.g. /var/www/ysupload.com/files/123F-16.zip
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
        Schema::drop('files');
    }

}
