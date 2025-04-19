<?php

namespace Cli\Commands;
use function stub;

class MakeMiddlewareCommand implements CommandInterface
{
    public static function name(): string
    {
        return 'make:middleware';
    }

    public static function description(): string
    {
        return 'Generate middleware constraint class';
    }

    public static function handle(array $argv): void
    {
        $class = $argv[2] ?? null;
        if (!$class) {
            echo "Usage: make:middleware <Name>\n";
            return;
        }

        $path = "app/Constraints/{$class}.php";
        file_put_contents($path, stub("middleware.stub.php", ['class' => $class]));
        echo " Middleware created: {$class}\n";
    }
}