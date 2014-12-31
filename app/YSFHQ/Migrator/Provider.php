<?php namespace YSFHQ\Migrator;

use Illuminate\Support\ServiceProvider;

class Provider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('YSFHQ\Infrastructure\Clients\PhpbbClient', 'YSFHQ\Infrastructure\Clients\PhpbbClient');
    }
}
