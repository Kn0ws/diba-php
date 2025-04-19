<?php

namespace Cli\Commands;

use function stub;
use function slugify;

class MakeIntentCommand implements CommandInterface
{
    public static function name(): string
    {
        return 'make:intent';
    }

    public static function description(): string
    {
        return 'Generate a new intent + executor';
    }

    public static function handle(array $argv): void
    {
        $name = $argv[2] ?? null;
        if (!$name) {
            echo "Usage: make:intent <Name>\n";
            return;
        }

        $slug = slugify($name);
        $intent = $name;
        $executor = $name . 'Executor';

        $yaml = __DIR__ . "/../../app/Intents/{$slug}.intents.yaml";
        $exec = __DIR__ . "/../../app/Executors/{$executor}.php";

        file_put_contents($yaml, stub("intent.stub.yaml", compact('intent', 'slug', 'executor')));
        file_put_contents($exec, stub("executor.stub.php", ['class' => $executor]));

        echo " Intent + Executor created: {$slug}\n";
    }
}
