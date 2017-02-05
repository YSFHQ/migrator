<?php

namespace YSFHQ\Migrator;

use Illuminate\Support\Facades\Facade;

class ActivitiesFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'MigratorActivities';
    }
}
