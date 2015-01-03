<?php namespace YSFHQ\Migrator\Commands;

use YSFHQ\Migrator\Post;

class ImportPostCommand {

    /**
     * @var YSFHQ\Migrator\Post
     */
    public $post;

    /**
     * @param YSFHQ\Migrator\Post post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

}
