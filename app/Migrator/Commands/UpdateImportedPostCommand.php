<?php

namespace YSFHQ\Migrator\Commands;

use YSFHQ\Infrastructure\Clients\PhpbbClient;
use YSFHQ\Infrastructure\Command;

class UpdateImportedPostCommand extends Command
{
    private $phpbb_id, $username, $post_time;

    /**
     * UpdateImportedPostCommand constructor.
     * @param $phpbb_id
     * @param $username
     * @param $post_time
     */
    public function __construct($phpbb_id, $username, $post_time)
    {
        $this->phpbb_id = $phpbb_id;
        $this->username = $username;
        $this->post_time = $post_time;
    }

    /**
     * Handle the command.
     *
     * @param PhpbbClient $phpbb
     * @return void
     */
    public function handle(PhpbbClient $phpbb)
    {
        $phpbb_user_id = $phpbb->getUserIdByUsername($this->username);
        if ($phpbb_user_id) {
            $phpbb->updatePost($this->phpbb_id, ['poster_id' => $phpbb_user_id, 'post_time' => $this->post_time]);
        } else {
            $phpbb->updatePost($this->phpbb_id, ['post_time' => $this->post_time]);
        }
    }

}
