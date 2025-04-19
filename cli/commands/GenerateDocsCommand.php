<?php

namespace Cli\Commands;

use Diba\IntentResolver;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\MarkdownConverter;
use Symfony\Component\Yaml\Yaml;

class GenerateDocsCommand implements CommandInterface
{
    public static function name(): string
    {
        return 'generate:docs';
    }

    public static function description(): string
    {
        return 'Generate markdown/html/json documentation for all intents';
    }

    public static function handle(array $argv): void
    {
        $pluginOnly = in_array('--plugin-only', $argv, true);
        $jsonFormat = in_array('--format=json', $argv, true);

        $resolver = new IntentResolver(collectIntentDirs());
        $intents = $resolver->getAll();

        $jsonArray = [];
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

        $output = "# Intent Documentation\n\n";

        foreach ($intents as $intent) {
            if (!$hasMatchingTag($intent)) continue;
            if ($pluginOnly && empty($intent->plugin)) continue;

            $name = $intent->name;
            $slug = strtolower($name);
            $desc = $intent->config['description'] ?? '-';
            $tags = implode(', ', $intent->config['tags'] ?? []);
            $match = $intent->config['match'] ?? [];
            $method = $match['method'] ?? 'GET';
            $path = $match['path'] ?? '-';
            $executors = implode(', ', $intent->config['executors'] ?? []);
            $response = is_array($intent->config['response'] ?? '') ? json_encode($intent->config['response']) : ($intent->config['response'] ?? '');

            $middlewares = $intent->config['middlewares'] ?? [];
            $before = implode(', ', $middlewares['before'] ?? []);
            $after  = implode(', ', $middlewares['after'] ?? []);

            $pluginSource = $intent->plugin ?? null;

            $output .= "## {$name}\n";
            $output .= "**Description**: {$desc}\n\n";
            $output .= "- **Tags**: {$tags}\n";
            $output .= "- **Method**: `{$method}`\n";
            $output .= "- **Path**: `{$path}`\n";
            $output .= "- **Executors**: `{$executors}`\n";
            $output .= "- **Response**: `{$response}`\n";
            if ($before || $after) {
                $output .= "- **Middlewares:**\n";
                if ($before) $output .= "  - Before: {$before}\n";
                if ($after)  $output .= "  - After:  {$after}\n";
            }
            if ($pluginSource) {
                $output .= "- **Plugin**: {$pluginSource}\n";
            }

            $testPath = __DIR__ . "/../../tests/{$slug}.test.yaml";
            if (file_exists($testPath)) {
                try {
                    $testYaml = Yaml::parseFile($testPath);
                    $example = Yaml::dump([
                        'input' => $testYaml['input'] ?? [],
                        'expected' => $testYaml['expected'] ?? $testYaml['expect'] ?? [],
                    ], 4, 2);
                    $output .= "\n**Example:**\n\n";
                    $output .= "```yaml\n{$example}```\n";
                } catch (\Throwable $e) {
                    $output .= "\n**Example:** (failed to parse test file)\n";
                }
            }

            $output .= "\n\n";

            $jsonArray[] = [
                'name' => $intent->name,
                'plugin' => $intent->plugin,
                'description' => $intent->config['description'] ?? '',
                'tags' => $intent->config['tags'] ?? [],
                'method' => $match['method'] ?? 'GET',
                'path' => $match['path'] ?? '-',
                'executors' => $intent->config['executors'] ?? [],
                'response' => $intent->config['response'] ?? null,
                'middlewares' => $intent->config['middlewares'] ?? [],
            ];
        }

        if ($jsonFormat) {
            file_put_contents('docs/intents.json', json_encode($jsonArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            echo " Output: docs/intents.json\n";
            return;
        }

        $docPath = 'docs/intents.md';
        file_put_contents($docPath, $output);

        $environment = new Environment();
        $environment->addExtension(new CommonMarkCoreExtension());
        $converter = new MarkdownConverter($environment);
        $htmlBody = $converter->convert($output)->getContent();

        $htmlTemplate = <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>DIBA Intent Documentation</title>
            <style>
                body { font-family: sans-serif; line-height: 1.6; padding: 2em; max-width: 1000px; margin: auto; }
                code, pre { background: #f5f5f5; padding: 0.5em; display: block; white-space: pre-wrap; }
                h1, h2, h3 { border-bottom: 1px solid #ccc; padding-bottom: 0.2em; }
            </style>
        </head>
        <body>
            {$htmlBody}
        </body>
        </html>
        HTML;

        file_put_contents('docs/intents.html', $htmlTemplate);
        echo " Output: docs/intents.md + docs/intents.html\n";
    }
}