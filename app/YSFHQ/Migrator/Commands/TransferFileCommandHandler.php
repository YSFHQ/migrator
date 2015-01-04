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
     * @return void
     */
    public function handle($command)
    {
        // copy the file
        if (copy($file->local_path, '/var/www/forum.ysfhq.com/files/'.$file->physical_path)) {
            // add the attachment to the post
            $file->phpbb_attachment_id = $this->phpbb->saveAttachment($file);
            $file->save();
        }
    }

}
