<?php

use Monolog\Handler\StreamHandler;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
     */

    'default'  => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
     */

    'channels' => [
        'stack'          => [
            'driver'   => 'stack',
            'channels' => ['daily', 'slack'],
        ],

        'single'         => [
            'driver' => 'single',
            'path'   => storage_path('logs/laravel.log'),
            'level'  => 'debug',
        ],

        'bugsnag'        => [
            'driver' => 'bugsnag',
        ],

        'daily'          => [
            'driver' => 'daily',
            'path'   => storage_path('logs/laravel.log'),
            'level'  => 'debug',
            'days'   => 7,
        ],

        'slack'          => [
            'driver'   => 'slack',
            'url'      => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji'    => ':boom:',
            'level'    => 'critical',
        ],

        'stderr'         => [
            'driver'  => 'monolog',
            'handler' => StreamHandler::class,
            'with'    => [
                'stream' => 'php://stderr',
            ],
        ],

        'syslog'         => [
            'driver' => 'syslog',
            'level'  => 'debug',
        ],

        'errorlog'       => [
            'driver' => 'errorlog',
            'level'  => 'debug',
        ],

        /* Custom log files */

        'listMagento'    => [
            'driver' => 'daily',
            'path'   => storage_path('logs/list-magento.log'),
            'days'   => 7,
        ],

        'productUpdates' => [
            'driver' => 'daily',
            'path'   => storage_path('logs/product-updates.log'),
            'days'   => 7,
        ],

        'chatapi'        => [
            'driver' => 'daily',
            'path'   => storage_path('logs/chatapi/chatapi.log'),
            'level'  => 'debug',
            'days'   => 7,
        ],

        'customerDnd'    => [
            'driver' => 'daily',
            'path'   => storage_path('logs/customers/dnd.log'),
            'level'  => 'debug',
        ],

        'customer'       => [
            'driver' => 'daily',
            'path'   => storage_path('logs/general/general.log'),
            'level'  => 'debug',
            'days'   => 7,
        ],

        'whatsapp'       => [
            'driver' => 'daily',
            'path'   => storage_path('logs/whatsapp/whatsapp.log'),
            'days'   => 7,
        ],
        'scraper'        => [
            'driver' => 'daily',
            'path'   => storage_path('logs/scraper/scraper.log'),
            'days'   => 7,
        ],
        'update_category_job'        => [
            'driver' => 'daily',
            'path'   => storage_path('logs/category_job/category_job.log'),
            'days'   => 7,
        ],
        'update_color_job'        => [
            'driver' => 'daily',
            'path'   => storage_path('logs/color_job/color_job.log'),
            'days'   => 7,
        ],
        'broadcast_log' => [
            'driver' => 'daily',
            'path'   => storage_path('logs/general/broadcast.log'),
            'days'   => 1,
        ],
        'hubstaff_activity_command' => [
            'driver' => 'daily',
            'path'   => storage_path('logs/hubstaff-activity-command/hubstaff-activity-command.log'),
            'days'   => 7,
        ],

        'scrapper_images'    => [
            'driver' => 'daily',
            'path'   => storage_path('logs/scrapper_images/scrapper_images.log'),
            'days'   => 7,
        ],
    ],

];
