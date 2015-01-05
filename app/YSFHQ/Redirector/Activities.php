<?php namespace YSFHQ\Redirector;

use Illuminate\Support\Facades\Log,
    Illuminate\Support\Facades\Request;

use YSFHQ\Migrator\Attachment,
    YSFHQ\Migrator\Post,
    YSFHQ\Redirector\Path;

class Activities
{

    public function redirect($path)
    {
        echo $path.'<br>';
        echo Request::fullUrl();
    }

    public function checkForRedirect($route = '')
    {
        // look up the route in our Path model
        //
        // if we can't find it, let's log the request
        // and redirect them as if they went to domain with no specified path
    }

}
