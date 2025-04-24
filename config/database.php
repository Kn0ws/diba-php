<?php

return [
    'default' => getenv('DB_CONNECTION') ?: 'mysql',

    'connections' => [
        'mysql' => [
            'driver'   => 'mysql',
            'host'     => getenv('DB_HOST') ?: '127.0.0.1',
            'port'     => getenv('DB_PORT') ?: '3306',
            'database' => getenv('DB_DATABASE') ?: 'mydb',
            'username' => getenv('DB_USERNAME') ?: 'root',
            'password' => getenv('DB_PASSWORD') ?: '',
            'charset'  => 'utf8mb4',
        ],

        'pgsql' => [
            'driver'   => 'pgsql',
            'host'     => getenv('DB_HOST') ?: '127.0.0.1',
            'port'     => getenv('DB_PORT') ?: '5432',
            'database' => getenv('DB_DATABASE') ?: 'mydb',
            'username' => getenv('DB_USERNAME') ?: 'postgres',
            'password' => getenv('DB_PASSWORD') ?: '',
        ],

        'sqlite' => [
            'driver'   => 'sqlite',
            'database' => getenv('DB_DATABASE') ?: __DIR__ . '/../database.sqlite',
        ],

        'sqlsrv' => [
            'driver'   => 'sqlsrv',
            'host'     => getenv('DB_HOST') ?: 'localhost',
            'port'     => getenv('DB_PORT') ?: '1433',
            'database' => getenv('DB_DATABASE') ?: 'mydb',
            'username' => getenv('DB_USERNAME') ?: 'sa',
            'password' => getenv('DB_PASSWORD') ?: '',
        ],
    ],
];
