<?php namespace YSFHQ\Infrastructure\Clients;

use \Exception;

use YSFHQ\Migrator\Post;

class PhpbbClient extends DatabaseClient
{

    private $per_page = 1000;

    public function getPosts($page = 1)
    {
        return $this->getConnection('phpbb')
            ->table('phpbb_posts')
            ->skip($this->per_page * ($page - 1))->take($this->per_page)
            ->get();
    }

    public function updatePost($id = null, $attributes = [])
    {
        return $this->getConnection('phpbb')
            ->table('phpbb_posts')
            ->where('post_id', $id)
            ->update($attributes);
    }

    public function makePost(Post $post = null)
    {
        if ($post) {
            return 1; // phpBB post ID
        }
        return false;
    }

}
