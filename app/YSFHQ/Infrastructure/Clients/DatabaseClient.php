<?php namespace YSFHQ\Infrastructure\Clients;

use \Exception;

class DatabaseClient
{

    protected $per_page = 100;
    protected $page = 1;

    protected function getConnection($conn = '')
    {
        return \DB::connection($conn);
    }

}
