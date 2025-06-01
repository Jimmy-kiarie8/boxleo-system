<?php

return [

    'storage' => [
        'database' => [
            'connection' => 'mysql',
        ],
    ],
    'key_path' => env('OAUTH_KEY_PATH', 'storage')

];
