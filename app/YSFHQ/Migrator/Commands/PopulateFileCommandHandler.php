<?php namespace YSFHQ\Migrator\Commands;

use Carbon\Carbon,
    Laracasts\Commander\CommandHandler,
    YSFHQ\Infrastructure\Clients\PhpbbClient,
    YSFHQ\Infrastructure\Clients\YSUploadClient,
    YSFHQ\Migrator\Attachment,
    YSFHQ\Migrator\Post;

class PopulateFileCommandHandler implements CommandHandler {

    private $phpbb;
    private $ysupload;

    public function __construct(PhpbbClient $phpbb, YSUploadClient $ysupload)
    {
        $this->phpbb = $phpbb;
        $this->ysupload = $ysupload;
    }

    /**
     * Handle the command.
     * Create File model to be queued for transfer.
     *
     * @param object $command
     * @return int The ID of the new File created.
     */
    public function handle($command)
    {
        $post = $command->post;

        $post_data = $this->phpbb->getPostDataFromId($post->phpbb_id);
        $file_data = $this->ysupload->getFileDataFromId($post->legacy_id);
        if (is_null($file_data)) {
            return null;
        }

        $file = new Attachment();
        $file->post_id = $post->id;
        $file->local_path = $file_data->local_path;

        // phpBB columns START
        $file->post_msg_id          = $post->phpbb_id;
        $file->topic_id             = $post_data->topic_id;
        $file->poster_id            = $post_data->poster_id;
        $file->physical_filename    = $post_data->poster_id.'_'.md5(mt_rand().'_'.uniqid());
        $file->real_filename        = $file_data->filename;
        $file->download_count       = $file_data->downloads;
        $file->extension            = $file_data->extension;
        $file->mimetype             = $file_data->mimetype;
        $file->filesize             = $file_data->filesize;
        $file->filetime             = $post_data->post_time;
        // phpbb columns END

        $file->save();

        return $file->id;
    }

}
