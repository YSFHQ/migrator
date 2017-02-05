<?php

namespace YSFHQ\Infrastructure\Clients;

use Illuminate\Support\Facades\DB;

class DatabaseClient
{

    public function __construct($per_page = 100, $page = 1)
    {
        $this->per_page = 100;
        $this->page = 1;
    }

    protected function getConnection($conn = '')
    {
        return DB::connection($conn);
    }

}
