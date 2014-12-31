<?php namespace YSFHQ\Infrastructure\Clients;

use \Exception;

class PhpbbClient
{

    private $per_page = 1000;
    private $page = 1;

    public function getPosts($page = 1)
    {
        return \DB::connection('phpbb')->table('phpbb_posts')->skip($this->per_page * ($page - 1))->take($this->per_page)->get();
    }

    public function updatePost($id = null, $attributes = [])
    {
        return \DB::connection('phpbb')->table('phpbb_posts')->where('post_id', $id)->update($attributes);
    }

}
