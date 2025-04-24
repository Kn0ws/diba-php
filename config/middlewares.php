<?php

return [
    'before' => [
        Diba\Middlewares\RequestLogger::class,
    ],
    'after' => [
        // App\Middleware\ResponseLogger::class,
    ],
];
