<?php namespace YSFHQ\Migrator;

use Illuminate\Support\ServiceProvider;

class Provider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('YSFHQ\Infrastructure\Clients\DrupalClient', 'YSFHQ\Infrastructure\Clients\DrupalClient');
        $this->app->bind('YSFHQ\Infrastructure\Clients\PhpbbClient', 'YSFHQ\Infrastructure\Clients\PhpbbClient');
        $this->app->bind('YSFHQ\Infrastructure\Clients\YSUploadClient', 'YSFHQ\Infrastructure\Clients\YSUploadClient');
        $this->app->bind('MigratorActivities', function () {
            return new Activities();
        });
    }
}
