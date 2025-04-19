<?php

namespace Cli\Commands;

use function stub;

class MakeConstraintCommand implements CommandInterface
{
    public static function name(): string
    {
        return 'make:constraint';
    }

    public static function description(): string
    {
        return 'Generate a new constraint class';
    }

    public static function handle(array $argv): void
    {
        $class = $argv[2] ?? null;
        if (!$class) {
            echo "Usage: make:constraint <Name>\n";
            return;
        }

        $file = __DIR__ . "/../../app/Constraints/{$class}.php";
        file_put_contents($file, stub("constraint.stub.php", ['class' => $class]));
        echo " Constraint created: {$class}\n";
    }
}
