<?php

namespace Database;

use Illuminate\Database\Capsule\Manager as Capsule;

class EloquentBootstrap
{
    protected static bool $booted = false;

    public static function boot(string $connection = null): Capsule
    {
        if (self::$booted) {
            return new Capsule();
        }

        $config = require __DIR__ . '/../config/database.php';
        $envConnection = getenv('DB_CONNECTION') ?: $config['default'];
        $connection = $connection ?? $envConnection;

        $capsule = new Capsule();

        if (!isset($config['eloquent'][$connection])) {
            throw new \Exception("DB connection not defined: {$connection}");
        }

        $capsule->addConnection($config['eloquent'][$connection]);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        self::$booted = true;

        return $capsule;
    }
}
