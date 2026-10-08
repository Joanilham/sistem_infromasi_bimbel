<?php

return [
    'enabled' => env('DEBUGBAR_ENABLED', false),
    'storage' => [
        'enabled' => false,
    ],
    'collectors' => [
        'phpinfo' => false,
        'messages' => false,
        'time' => false,
        'memory' => false,
        'exceptions' => false,
        'log' => false,
        'db' => false,
        'views' => false,
        'route' => false,
        'auth' => false,
        'gate' => false,
        'session' => false,
        'symfony_request' => false,
        'mail' => false,
        'laravel' => false,
        'events' => false,
        'default_request' => false,
        'logs' => false,
        'files' => false,
        'config' => false,
        'cache' => false,
        'models' => false,
        'livewire' => false,
        'jobs' => false,
    ],
];
