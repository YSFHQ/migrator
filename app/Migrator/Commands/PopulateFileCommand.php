<?php

namespace YSFHQ\Migrator\Commands;

use YSFHQ\Infrastructure\Clients\PhpbbClient,
    YSFHQ\Infrastructure\Clients\YSUploadClient,
    YSFHQ\Migrator\Attachment,
    YSFHQ\Migrator\Post;
use YSFHQ\Infrastructure\Command;

class PopulateFileCommand extends Command
{

    private $post;

    /**
     * PopulateFileCommand constructor.
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Handle the command.
     * Create File model to be queued for transfer.
     *
     * @param PhpbbClient $phpbb
     * @param YSUploadClient $ysupload
     * @return int The ID of the new File created.
     */
    public function handle(PhpbbClient $phpbb, YSUploadClient $ysupload)
    {
        $post = $this->post;

        $post_data = $phpbb->getPostDataFromId($post->phpbb_id);
        $file_data = $ysupload->getFileDataFromId($post->legacy_id);
        if (is_null($file_data)) {
            return null;
        }

        $file = new Attachment();
        $file->post_id = $post->id;
        $file->local_path = $file_data->local_path;

        // phpBB columns START
        $file->post_msg_id = $post->phpbb_id;
        $file->topic_id = $post_data->topic_id;
        $file->poster_id = $post_data->poster_id;
        $file->physical_filename = $post_data->poster_id . '_' . md5(mt_rand() . '_' . uniqid());
        $file->real_filename = $file_data->filename;
        $file->download_count = $file_data->downloads;
        $file->extension = $file_data->extension;
        $file->mimetype = $file_data->mimetype;
        $file->filesize = $file_data->filesize;
        $file->filetime = $post_data->post_time;
        // phpbb columns END

        $file->save();

        return $file->id;
    }

}
