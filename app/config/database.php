<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | PDO Fetch Style
    |--------------------------------------------------------------------------
    |
    | By default, database results will be returned as instances of the PHP
    | stdClass object; however, you may desire to retrieve records in an
    | array format for simplicity. Here you can tweak the fetch style.
    |
    */

    'fetch' => PDO::FETCH_CLASS,

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => 'mysql',

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => array(

        'drupal' => array(
            'driver'    => $_ENV['connections.drupal.driver'],
            'host'      => $_ENV['connections.drupal.host'],
            'database'  => $_ENV['connections.drupal.database'],
            'username'  => $_ENV['connections.drupal.username'],
            'password'  => $_ENV['connections.drupal.password'],
            'charset'   => $_ENV['connections.drupal.charset'],
            'collation' => $_ENV['connections.drupal.collation'],
            'prefix'    => $_ENV['connections.drupal.prefix'],
        ),

        'phpbb' => array(
            'driver'    => $_ENV['connections.phpbb.driver'],
            'host'      => $_ENV['connections.phpbb.host'],
            'database'  => $_ENV['connections.phpbb.database'],
            'username'  => $_ENV['connections.phpbb.username'],
            'password'  => $_ENV['connections.phpbb.password'],
            'charset'   => $_ENV['connections.phpbb.charset'],
            'collation' => $_ENV['connections.phpbb.collation'],
            'prefix'    => $_ENV['connections.phpbb.prefix'],
        ),

        'wordpress' => array(
            'driver'    => $_ENV['connections.wordpress.driver'],
            'host'      => $_ENV['connections.wordpress.host'],
            'database'  => $_ENV['connections.wordpress.database'],
            'username'  => $_ENV['connections.wordpress.username'],
            'password'  => $_ENV['connections.wordpress.password'],
            'charset'   => $_ENV['connections.wordpress.charset'],
            'collation' => $_ENV['connections.wordpress.collation'],
            'prefix'    => $_ENV['connections.wordpress.prefix'],
        ),

        'ysupload' => array(
            'driver'    => $_ENV['connections.ysupload.driver'],
            'host'      => $_ENV['connections.ysupload.host'],
            'database'  => $_ENV['connections.ysupload.database'],
            'username'  => $_ENV['connections.ysupload.username'],
            'password'  => $_ENV['connections.ysupload.password'],
            'charset'   => $_ENV['connections.ysupload.charset'],
            'collation' => $_ENV['connections.ysupload.collation'],
            'prefix'    => $_ENV['connections.ysupload.prefix'],
        ),

    ),

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => array(

        'cluster' => false,

        'default' => array(
            'host'     => '127.0.0.1',
            'port'     => 6379,
            'database' => 0,
        ),

    ),

);
