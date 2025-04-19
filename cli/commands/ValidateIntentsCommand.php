<?php

namespace Cli\Commands;

use Diba\IntentResolver;

class ValidateIntentsCommand implements CommandInterface
{
    public static function name(): string
    {
        return 'validate:intents';
    }

    public static function description(): string
    {
        return 'Validate required keys in intent definitions';
    }

    public static function handle(array $argv): void
    {
        echo " Validating intents...\n";

        $resolver = new IntentResolver(collectIntentDirs());
        $errors = [];

        foreach ($resolver->getAll() as $intent) {
            $config = $intent->config;
            $name = $intent->name;
            $issues = [];

            if (empty($config['intent']) || !is_string($config['intent'])) {
                $issues[] = 'Missing or invalid "intent"';
            }

            if (empty($config['match']['method'])) {
                $issues[] = 'Missing match.method';
            }

            if (empty($config['match']['path'])) {
                $issues[] = 'Missing match.path';
            }

            if (empty($config['executors']) || !is_array($config['executors'])) {
                $issues[] = 'Missing or invalid "executors"';
            }

            if ($issues) {
                $errors[$name] = $issues;
            }
        }

        if ($errors) {
            echo " Invalid Intents found:\n";
            foreach ($errors as $name => $issues) {
                echo "- {$name}\n";
                foreach ($issues as $msg) {
                    echo "  - {$msg}\n";
                }
            }
            exit(1);
        }

        echo " All intents are valid.\n";
    }
}
