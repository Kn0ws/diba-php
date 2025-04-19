<?php

namespace Cli\Commands;

use Diba\IntentResolver;

class GraphIntentsCommand implements CommandInterface
{
    public static function name(): string
    {
        return 'graph:intents';
    }

    public static function description(): string
    {
        return 'Generate Mermaid graph of intent event relationships';
    }

    public static function handle(array $argv): void
    {
        $resolver = new IntentResolver(collectIntentDirs());
        $intents = $resolver->getAll();

        $filterTags = [];
        if (($tagIdx = array_search('--tag', $argv)) !== false && isset($argv[$tagIdx + 1])) {
            $filterTags[] = $argv[$tagIdx + 1];
        }
        if (($includeIdx = array_search('--include-tags', $argv)) !== false && isset($argv[$includeIdx + 1])) {
            $filterTags = explode(',', $argv[$includeIdx + 1]);
        }

        $hasMatchingTag = function ($intent) use ($filterTags) {
            if (empty($filterTags)) return true;
            $tags = $intent->config['tags'] ?? [];
            return count(array_intersect($tags, $filterTags)) > 0;
        };

        $lines = ["graph TD"];
        foreach ($intents as $sourceIntent) {
            if (!$hasMatchingTag($sourceIntent)) continue;

            foreach ($sourceIntent->getEffects() as $eventName) {
                foreach ($intents as $targetIntent) {
                    if (!$hasMatchingTag($targetIntent)) continue;
                    $map = $targetIntent->getEffectListeners();
                    if (($map[$eventName] ?? null) === $targetIntent->name) {
                        $lines[] = "{$sourceIntent->name} --> {$targetIntent->name}";
                    }
                }
            }
        }

        $output = implode("\n", $lines);

        if (($i = array_search('--save', $argv)) !== false && isset($argv[$i + 1])) {
            file_put_contents($argv[$i + 1], $output);
            echo " Mermaid graph saved to {$argv[$i + 1]}\n";
        } else {
            echo $output . "\n";
        }
    }
}
