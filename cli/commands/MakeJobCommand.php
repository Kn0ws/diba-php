<?php

namespace Cli\Commands;

use function stub;

class MakeJobCommand implements CommandInterface
{
    public static function name(): string
    {
        return 'make:job';
    }

    public static function description(): string
    {
        return 'Generate job Executor and Intent';
    }

    public static function handle(array $argv): void
    {
        $name = $argv[2] ?? null;
        if (!$name) {
            echo "Usage: php diba.php make:job <Name>\n";
            return;
        }

        $execCode = stub('job_executor.stub.php', ['DummyJob' => $name]);
        $intentCode = stub('job_intent.stub.yaml', ['DummyJob' => $name]);

        file_put_contents("app/Executors/{$name}Executor.php", $execCode);
        file_put_contents("app/Intents/{$name}.yaml", $intentCode);

        echo " Job Executor + Intent generated for: {$name}\n";
    }
}