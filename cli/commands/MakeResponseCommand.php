<?php

namespace Cli\Commands;

use function stub;

class MakeResponseCommand implements CommandInterface
{
    public static function name(): string
    {
        return 'make:response';
    }

    public static function description(): string
    {
        return 'Generate a new response class';
    }

    public static function handle(array $argv): void
    {
        $class = $argv[2] ?? null;
        if (!$class) {
            echo "Usage: make:response <Name>\n";
            return;
        }

        $file = __DIR__ . "/../../app/Responses/{$class}.php";
        file_put_contents($file, stub("response.stub.php", ['class' => $class]));
        echo " Response created: {$class}\n";
    }
}
