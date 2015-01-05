<?php namespace YSFHQ\Migrator\Commands;

use Laracasts\Commander\CommandHandler;
use YSFHQ\Infrastructure\Clients\PhpbbClient;

class UpdateDownloadLinkCommandHandler implements CommandHandler {

    private $phpbb;

    public function __construct(PhpbbClient $phpbb)
    {
        $this->phpbb = $phpbb;
    }

    /**
     * Handle the command.
     *
     * @param object $command
     * @return void
     */
    public function handle($command)
    {
        $post_data = $this->phpbb->getPostDataFromId($command->phpbb_id);
        $post_body = preg_replace("/\[size=150:(.*?)\]\[url\=(.*?)\](.*?)\[\/url(.*?)\]\[\/size:(.*?)\]/", "Click the attachment below to download the addon.", $post_data->post_text);
        $this->phpbb->updatePost($post_data->post_id, ['post_text' => $post_body]);
    }

}
