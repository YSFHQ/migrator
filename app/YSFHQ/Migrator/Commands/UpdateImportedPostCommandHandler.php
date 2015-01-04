<?php namespace YSFHQ\Migrator\Commands;

use Laracasts\Commander\CommandHandler;
use YSFHQ\Infrastructure\Clients\PhpbbClient;

class UpdateImportedPostCommandHandler implements CommandHandler {

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
        $phpbb_user_id = $this->phpbb->getUserIdByUsername($command->username);
        if ($phpbb_user_id) {
            $this->phpbb->updatePost($command->phpbb_id, ['poster_id' => $phpbb_user_id, 'post_time' => $command->post_time]);
        } else {
            $this->phpbb->updatePost($command->phpbb_id, ['post_time' => $command->post_time]);
        }
    }

}
