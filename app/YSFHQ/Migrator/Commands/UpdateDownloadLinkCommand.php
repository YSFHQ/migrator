<?php namespace YSFHQ\Migrator\Commands;

class UpdateDownloadLinkCommand {

    /**
     * @var string
     */
    public $phpbb_id;

    /**
     * @var string
     */
    public $attachment_id;

    /**
     * @param string phpbb_id
     * @param string attachment_id
     */
    public function __construct($phpbb_id, $attachment_id)
    {
        $this->phpbb_id = $phpbb_id;
        $this->attachment_id = $attachment_id;
    }

}