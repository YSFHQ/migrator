<?php namespace YSFHQ\Migrator\Commands;

class UpdateImportedPostCommand {

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $phpbb_id;

    /**
     * @param string username
     * @param string phpbb_id
     */
    public function __construct($username, $phpbb_id)
    {
        $this->username = $username;
        $this->phpbb_id = $phpbb_id;
    }

}