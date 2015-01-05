<?php

use YSFHQ\Redirector\Activities as RedirectorActivities;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::any('{any}', function($route)
{
    $redirector = new RedirectorActivities();
	return Redirect::away($redirector->getRedirectUrl($route), 301);
})->where('any', '(.*)');
