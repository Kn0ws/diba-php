<?php

namespace Cli\Commands;

class RunQueueCommand implements CommandInterface
{
    public static function name(): string
    {
        return 'run:queue';
    }

    public static function description(): string
    {
        return 'Run job queue worker';
    }

    public static function handle(array $argv): void
    {
        require_once __DIR__ . '/../worker.php';
    }
}