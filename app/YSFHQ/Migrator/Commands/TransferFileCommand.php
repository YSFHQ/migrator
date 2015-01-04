<?php namespace YSFHQ\Migrator\Commands;

use YSFHQ\Migrator\File;

class TransferFileCommand {

    /**
     * @var YSFHQ\Migrator\File
     */
    public $file;

    /**
     * @param YSFHQ\Migrator\File file
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }

}
