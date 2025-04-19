<?php

namespace Cli\Commands;

use Diba\IntentResolver;

class ListIntentsCommand implements CommandInterface
{
    public static function name(): string
    {
        return 'list:intents';
    }

    public static function description(): string
    {
        return 'List all registered intents';
    }

    public static function handle(array $argv): void
    {
        $resolver = new IntentResolver(collectIntentDirs());
        foreach ($resolver->getAll() as $i) {
            $desc = $i->config['description'] ?? '-';
            $tags = $i->config['tags'] ?? [];
            $tagText = $tags ? '[' . implode(', ', $tags) . ']' : '';
            echo "- {$i->name} {$tagText}\n  {$desc}\n";
        }
    }
}
