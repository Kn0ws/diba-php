<?php

namespace Cli\Commands;

class MakePluginCommand implements CommandInterface
{
    public static function name(): string
    {
        return 'make:plugin';
    }

    public static function description(): string
    {
        return 'Generate plugin structure (Intent/Executor/Service)';
    }

    public static function handle(array $argv): void
    {
        $name = $argv[2] ?? null;
        if (!$name) {
            echo "Usage: make:plugin <PluginName>\n";
            return;
        }

        $slug = strtolower($name);
        $base = "plugins/{$name}";

        if (!is_dir($base)) {
            mkdir("{$base}/Intents", 0777, true);
            mkdir("{$base}/Executors", 0777, true);
            mkdir("{$base}/Services", 0777, true);
        }

        file_put_contents("{$base}/plugin.json", json_encode([
            'name' => $name,
            'version' => '1.0.0',
            'autoload' => true,
            'intents' => 'Intents',
            'executors' => 'Executors',
            'services' => 'Services',
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        file_put_contents("{$base}/Executors/{$name}Executor.php", stub("plugin_executor.stub.php", [
            'plugin' => $name,
            'slug' => $slug,
        ]));

        file_put_contents("{$base}/Intents/{$slug}.yaml", stub("plugin_intent.stub.yaml", [
            'plugin' => $name,
            'slug' => $slug,
        ]));

        file_put_contents("{$base}/Services/{$name}Service.php", stub("plugin_service.stub.php", [
            'plugin' => $name,
            'slug' => $slug,
        ]));

        echo " Plugin created: {$name}\n";
    }
}