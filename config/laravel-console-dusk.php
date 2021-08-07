<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Laravel Console Dusk Paths
    |--------------------------------------------------------------------------
    |
    | Here you may configure the name of screenshots and logs directory as you wish.
    */
    'paths' => [
        'screenshots' => public_path('screenshots'),
        'log'         => storage_path('laravel-console-dusk/log'),
    ],
];
