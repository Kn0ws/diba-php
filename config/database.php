<?php

return [
    'default' => getenv('DB_CONNECTION') ?: 'mysql',

    // for Eloquent Connection.
    'eloquent' => [

        'mysql' => [
            'driver'    => 'mysql',
            'host'      => getenv('DB_HOST') ?: '127.0.0.1',
            'port'      => getenv('DB_PORT') ?: 3306,
            'database'  => getenv('DB_DATABASE') ?: 'app',
            'username'  => getenv('DB_USERNAME') ?: 'root',
            'password'  => getenv('DB_PASSWORD') ?: '',
            'charset'   => getenv('DB_CHARSET') ?: 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'prefix'    => '',
        ],

        'pgsql' => [
            'driver'    => 'pgsql',
            'host'      => getenv('DB_HOST') ?: '127.0.0.1',
            'port'      => getenv('DB_PORT') ?: 5432,
            'database'  => getenv('DB_DATABASE') ?: 'app',
            'username'  => getenv('DB_USERNAME') ?: 'postgres',
            'password'  => getenv('DB_PASSWORD') ?: '',
            'charset'   => getenv('DB_CHARSET') ?: 'utf8',
            'prefix'    => '',
        ],

        'sqlite' => [
            'driver'    => 'sqlite',
            'database'  => getenv('DB_DATABASE') ?: ':memory:',
            'prefix'    => '',
        ],

        'sqlsrv' => [
            'driver'    => 'sqlsrv',
            'host'      => getenv('DB_HOST') ?: 'localhost',
            'port'      => getenv('DB_PORT') ?: 1433,
            'database'  => getenv('DB_DATABASE') ?: 'app',
            'username'  => getenv('DB_USERNAME') ?: 'sa',
            'password'  => getenv('DB_PASSWORD') ?: '',
            'prefix'    => '',
        ],
    ],

    'connections' => [
    ],
];
