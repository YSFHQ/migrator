<?php namespace YSFHQ\Migrator\Commands;

use YSFHQ\Migrator\Attachment;

class TransferFileCommand {

    /**
     * @var YSFHQ\Migrator\File
     */
    public $file;

    /**
     * @param YSFHQ\Migrator\File file
     */
    public function __construct(Attachment $file)
    {
        $this->file = $file;
    }

}
