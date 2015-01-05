<?php namespace YSFHQ\Migrator\Commands;

use Laracasts\Commander\CommandHandler;
use YSFHQ\Infrastructure\Clients\PhpbbClient;

class TransferFileCommandHandler implements CommandHandler {

    private $phpbb;

    public function __construct(PhpbbClient $phpbb)
    {
        $this->phpbb = $phpbb;
    }

    /**
     * Handle the command.
     *
     * @param object $command
     * @return int|null The attachment ID from phpBB if transferred properly, otherwise null.
     */
    public function handle($command)
    {
        if (copy($file->local_path, '/var/www/forum.ysfhq.com/files/'.$file->physical_path)) {
            $file->phpbb_attachment_id = $this->phpbb->saveAttachment($file);
            if ($file->save()) {
                return $file->phpbb_attachment_id;
            }
        }
        return null;
    }

}
