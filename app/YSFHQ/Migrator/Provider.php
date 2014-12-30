<?php namespace YSFHQ\Migrator;

use Illuminate\Support\ServiceProvider;

class Provider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('YSFHQ\Clients\PhpbbClient', 'YSFHQ\Clients\PhpbbClient');
    }
}
