<?php namespace YSFHQ\Migrator\Commands;

use Laracasts\Commander\CommandHandler;
use YSFHQ\Infrastructure\Clients\PhpbbClient;

class ImportPostCommandHandler implements CommandHandler {

    private $phpbb;

    public function __construct(PhpbbClient $phpbb)
    {
        $this->phpbb = $phpbb;
    }

    /**
     * Handle the command.
     *
     * @param object $command
     * @return int $phpbb_post_id
     */
    public function handle($command)
    {
        $post = $command->post;
        return $this->phpbb->makePost([
            'forum_id' => $post->forum_id,
            'topic_id' => $post->topic_id,
            'subject'  => $post->subject,
            'body'     => $post->body
        ]);
    }

}
