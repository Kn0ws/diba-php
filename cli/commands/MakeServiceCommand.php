<?php

namespace Cli\Commands;

use function stub;

class MakeServiceCommand implements CommandInterface
{
    public static function name(): string
    {
        return 'make:service';
    }

    public static function description(): string
    {
        return 'Generate a new service class';
    }

    public static function handle(array $argv): void
    {
        $class = $argv[2] ?? null;
        if (!$class) {
            echo "Usage: make:service <Name>\n";
            return;
        }

        $path = __DIR__ . "/../../app/Services/{$class}.php";
        file_put_contents($path, stub("service.stub.php", ['class' => $class]));
        echo " Service created: {$class}\n";
    }
}
