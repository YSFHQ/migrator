<?php

namespace YSFHQ\Migrator\Commands;

use YSFHQ\Infrastructure\Clients\PhpbbClient;
use YSFHQ\Infrastructure\Command;
use YSFHQ\Migrator\Attachment;

class TransferFileCommand extends Command
{
    private $file;

    public function __construct(Attachment $file)
    {
        $this->file = $file;
    }

    /**
     * Handle the command.
     *
     * @param PhpbbClient $phpbb
     * @return int|null The attachment ID from phpBB if transferred properly, otherwise null.
     */
    public function handle(PhpbbClient $phpbb)
    {
        $file = $this->file;
        if (copy($file->local_path, '/var/www/forum.ysfhq.com/files/' . $file->physical_filename)) {
            $file->phpbb_attachment_id = $phpbb->saveAttachment($file);
            if ($file->save()) {
                return $file->phpbb_attachment_id;
            }
        }
        return null;
    }

}
