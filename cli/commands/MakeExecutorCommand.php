<?php

namespace Cli\Commands;

use function stub;

class MakeExecutorCommand implements CommandInterface
{
    public static function name(): string
    {
        return 'make:executor';
    }

    public static function description(): string
    {
        return 'Generate a new executor class';
    }

    public static function handle(array $argv): void
    {
        $class = $argv[2] ?? null;
        if (!$class) {
            echo "Usage: make:executor <Name>\n";
            return;
        }

        $file = __DIR__ . "/../../app/Executors/{$class}.php";
        file_put_contents($file, stub("executor.stub.php", ['class' => $class]));
        echo " Executor created: {$class}\n";
    }
}
