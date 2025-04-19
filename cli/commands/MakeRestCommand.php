<?php

namespace Cli\Commands;

use function stub;

class MakeRestCommand implements CommandInterface
{
    public static function name(): string
    {
        return 'make:rest';
    }

    public static function description(): string
    {
        return 'Generate RESTful Intent and Executor for a resource';
    }

    public static function handle(array $argv): void
    {
        $arg = $argv[2] ?? null;
        if (!$arg) {
            echo "Usage: make:rest <ResourceName>\n";
            return;
        }

        $name = ucfirst($arg);
        $plural = strtolower($name) . 's';

        $executorStub = stub('RestExecutor.stub.php', ['Dummy' => $name]);
        $intentStub   = stub('RestIntent.stub.yaml', ['Dummy' => $name, 'dummy' => strtolower($name)]);

        file_put_contents("app/Executors/{$name}ApiExecutor.php", $executorStub);
        file_put_contents("app/Intents/{$plural}.intents.yaml", $intentStub);

        shell_exec("php cli/diba.php make:model {$name}");

        echo " REST API generated for: {$name}\n";
    }
}