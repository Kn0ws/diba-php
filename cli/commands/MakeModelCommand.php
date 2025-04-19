<?php

namespace Cli\Commands;

use function stub;
use function slugify;

class MakeModelCommand implements CommandInterface
{
    public static function name(): string
    {
        return 'make:model';
    }

    public static function description(): string
    {
        return 'Generate a new model class';
    }

    public static function handle(array $argv): void
    {
        $class = $argv[2] ?? null;
        if (!$class) {
            echo "Usage: make:model <Name>\n";
            return;
        }

        $table = slugify($class);
        $path = __DIR__ . "/../../app/Models/{$class}.php";

        file_put_contents($path, stub("model.stub.php", compact('class', 'table')));
        echo " Model created: {$class} (table: {$table})\n";
    }
}
