<?php namespace YSFHQ\Migrator\Commands;

use Carbon\Carbon,
    Laracasts\Commander\CommandHandler,
    YSFHQ\Infrastructure\Clients\YSUploadClient;

class ExportYSUploadAddonDataCommandHandler implements CommandHandler {

    private $ysupload;

    public function __construct(YSUploadClient $ysupload)
    {
        $this->ysupload = $ysupload;
    }

    /**
     * Handle the command.
     *
     * @param object $command
     * @return void
     */
    public function handle($command)
    {

    }

}
