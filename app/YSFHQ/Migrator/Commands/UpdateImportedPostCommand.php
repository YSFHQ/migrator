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
     * @var string
     */
    public $post_time;

    /**
     * @param string username
     * @param string phpbb_id
     */
    public function __construct($phpbb_id, $username, $post_time)
    {
        $this->phpbb_id = $phpbb_id;
        $this->username = $username;
        $this->post_time = $post_time;
    }

}
