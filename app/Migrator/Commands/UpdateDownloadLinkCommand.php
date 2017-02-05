<?php

namespace YSFHQ\Migrator\Commands;

use YSFHQ\Infrastructure\Clients\PhpbbClient;
use YSFHQ\Infrastructure\Command;

class UpdateDownloadLinkCommand extends Command
{
    private $phpbb_id, $attachment_id;

    /**
     * @param string $phpbb_id
     * @param string $attachment_id
     */
    public function __construct($phpbb_id, $attachment_id)
    {
        $this->phpbb_id = $phpbb_id;
        $this->attachment_id = $attachment_id;
    }

    /**
     * Handle the command.
     *
     * @param PhpbbClient $phpbb
     * @return void
     */
    public function handle(PhpbbClient $phpbb)
    {
        $post_data = $phpbb->getPostDataFromId($this->phpbb_id);
        $post_body = preg_replace("/\[size=150:(.*?)\]\[url\=(.*?)\](.*?)\[\/url(.*?)\]\[\/size:(.*?)\]/", "Click the attachment below to download the addon.", $post_data->post_text);
        $phpbb->updatePost($post_data->post_id, ['post_text' => $post_body]);
    }

}
