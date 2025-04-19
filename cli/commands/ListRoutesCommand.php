<?php

namespace Cli\Commands;

use Diba\IntentResolver;

class ListRoutesCommand implements CommandInterface
{
    public static function name(): string
    {
        return 'list:routes';
    }

    public static function description(): string
    {
        return 'List all route patterns defined by intents';
    }

    public static function handle(array $argv): void
    {
        $resolver = new IntentResolver(collectIntentDirs());
        foreach ($resolver->getAll() as $i) {
            $m = $i->config['match']['method'] ?? 'GET';
            $p = $i->config['match']['path'] ?? '-';
            echo "{$m} {$p} -> {$i->name}\n";
        }
    }
}
