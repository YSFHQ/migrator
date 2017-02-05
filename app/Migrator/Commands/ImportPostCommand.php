<?php

namespace YSFHQ\Migrator\Commands;

use YSFHQ\Infrastructure\Clients\PhpbbClient;
use YSFHQ\Infrastructure\Command;
use YSFHQ\Migrator\Post;

class ImportPostCommand extends Command
{
    private $post;

    /**
     * ImportPostCommand constructor.
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Handle the command.
     *
     * @param PhpbbClient $phpbb
     * @return int The post_id from phpBB of the new post.
     */
    public function handle(PhpbbClient $phpbb)
    {
        $post = $this->post;
        return $phpbb->makePost([
            'forum_id' => $post->forum_id,
            'topic_id' => $post->topic_id,
            'subject'  => $post->subject,
            'body'     => $post->body
        ]);
    }

}
