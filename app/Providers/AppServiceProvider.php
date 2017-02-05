<?php

namespace YSFHQ\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('YSFHQ\Infrastructure\Clients\DrupalClient', 'YSFHQ\Infrastructure\Clients\DrupalClient');
        $this->app->bind('YSFHQ\Infrastructure\Clients\PhpbbClient', 'YSFHQ\Infrastructure\Clients\PhpbbClient');
        $this->app->bind('YSFHQ\Infrastructure\Clients\YSUploadClient', 'YSFHQ\Infrastructure\Clients\YSUploadClient');
        $this->app->bind('MigratorActivities', function () {
            return new \YSFHQ\Migrator\Activities();
        });
    }
}
