<?php

return [

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

    'default' => env('DB_CONNECTION', 'mysql'),

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

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
        ],

        'mysql' => [
            'read' => [
                'host' => [
                    env('DB_HOST_READ', '127.0.0.1'),
                ],
            ],
            'write' => [
                'host' => [
                    env('DB_HOST', '127.0.0.1'),
                ],
            ],
            'host'     => env('DB_HOST', '127.0.0.1'),
            'sticky' => true,
            'driver' => 'mysql',
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'erp'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'database' => env('DB_DATABASE', 'sololuxury'),
            'username' => env('DB_USERNAME', 'vaibhav'),
            'password' => env('DB_PASSWORD', 'jain'),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => 'InnoDB',
            'options' => [
                \PDO::ATTR_PERSISTENT => true
            ]
        ],
        'brandsandlabel' => [
            'driver'   => 'mysql',
            'host'     => env('BRANDS_HOST', 'erp'),
            'database' => env('BRANDS_DB', 'erp'),
            'username' => env('MAGENTO_DB_USER', 'root'),
            'password' => env('MAGENTO_DB_PASSWORD', ''),
            'strict' => false,
        ],
        'avoirchic' => [
            'driver'   => 'mysql',
            'host'     => env('AVOIRCHIC_HOST', 'erp'),
            'database' => env('AVOIRCHIC_DB', 'erp'),
            'username' => env('MAGENTO_DB_USER', 'root'),
            'password' => env('MAGENTO_DB_PASSWORD', ''),
            'strict' => false,
        ],
        'olabels' => [
            'driver'   => 'mysql',
            'host'     => env('OLABELS_HOST', 'erp'),
            'database' => env('OLABELS_DB', 'erp'),
            'username' => env('MAGENTO_DB_USER', 'root'),
            'password' => env('MAGENTO_DB_PASSWORD', ''),
            'strict' => false,
        ],
        'sololuxury' => [
            'driver'   => 'mysql',
            'host'     => env('SOLOLUXURY_HOST', 'erp'),
            'database' => env('SOLOLUXRY_DB', 'erp'),
            'username' => env('MAGENTO_DB_USER', 'root'),
            'password' => env('MAGENTO_DB_PASSWORD', ''),
            'strict' => false,
        ],
        'suvandnet' => [
            'driver'   => 'mysql',
            'host'     => env('SUVANDNAT_HOST', 'erp'),
            'database' => env('SUVANDNAT_DB', 'erp'),
            'username' => env('MAGENTO_DB_USER', 'root'),
            'password' => env('MAGENTO_DB_PASSWORD', ''),
            'strict' => false,
        ],
        'thefitedit' => [
            'driver'   => 'mysql',
            'host'     => env('THEFITEDIT_HOST', 'erp'),
            'database' => env('THEFITEDIT_DB', 'erp'),
            'username' => env('MAGENTO_DB_USER', 'root'),
            'password' => env('MAGENTO_DB_PASSWORD', ''),
            'strict' => false,
        ],
        'theshadesshop' => [
            'driver'   => 'mysql',
            'host'     => env('THESHADSSHOP_HOST', 'erp'),
            'database' => env('THESHADSSHOP_DB', 'erp'),
            'username' => env('MAGENTO_DB_USER', 'root'),
            'password' => env('MAGENTO_DB_PASSWORD', ''),
            'strict' => false,
        ],
        'upeau' => [
            'driver'   => 'mysql',
            'host'     => env('UPEAU_HOST', 'erp'),
            'database' => env('UPEAU_DB', 'erp'),
            'username' => env('MAGENTO_DB_USER', 'root'),
            'password' => env('MAGENTO_DB_PASSWORD', ''),
            'strict' => false,
        ],
        'veralusso' => [
            'driver'   => 'mysql',
            'host'     => env('VERALUSSO_HOST', 'erp'),
            'database' => env('VERALUSSO_DB', 'erp'),
            'username' => env('MAGENTO_DB_USER', 'root'),
            'password' => env('MAGENTO_DB_PASSWORD', ''),
            'strict' => false,
        ],
        'tracker' => [
            'driver'   => 'mysql',
            'host'     => 'localhost',
            'database' => env('DB_DATABASE', 'erp'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'strict' => false,    // to avoid problems on some MySQL installs
            'engine' => 'MyISAM',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ],
        'pgsql' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'erp'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
            'engine' => 'MyISAM',
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'erp'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'engine' => 'MyISAM',
        ],

    ],

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

    'redis' => [

        'client' => 'predis',

        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => 0,
            'read_write_timeout' => 0
        ],

    ],

];
