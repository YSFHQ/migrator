<?php namespace YSFHQ\Infrastructure\Clients;

use \Exception;

class DatabaseClient
{

    private $per_page = 500;
    private $page = 1;

    protected getConnection($conn = '')
    {
        return \DB::connection($conn);
    }

}
