<?php namespace YSFHQ\Infrastructure\Clients;

use \Exception;

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

    public function makeTopic()
    {
        throw new Exception("Unimplemented method");
    }

    public function makePost()
    {
        throw new Exception("Unimplemented method");
    }

}
