<?php
require_once __DIR__ . '/vendor/autoload.php';

$envFile = '.env';


if (file_exists(__DIR__ . '/' . $envFile)) {
    foreach (file(__DIR__ . '/' . $envFile) as $line) {
        if (preg_match('/^([A-Z_]+)=(.*)$/', trim($line), $matches)) {
            putenv("{$matches[1]}={$matches[2]}");
        }
    }
}

if (!getenv('APP_ENV')) {
    putenv('APP_ENV=production');
}

if (getenv('APP_TIMEZONE')) {
    date_default_timezone_set(getenv('APP_TIMEZONE'));
} else {
    date_default_timezone_set('UTC');
}

require_once __DIR__ . '/config/events.php';

if (!function_exists('class_basename')) {
    function class_basename(string $fqcn): string
    {
        return substr(strrchr($fqcn, "\\"), 1) ?: $fqcn;
    }
}


function collectIntentDirs(): array {
    $base = realpath(__DIR__);
    $dirs = [$base . '/app/Intents'];

    if (is_dir($base . '/plugins')) {
        foreach (glob($base . '/plugins/*/plugin.json') as $jsonFile) {
            $json = json_decode(file_get_contents($jsonFile), true);
            if (!($json['autoload'] ?? false)) continue;

            $pluginDir = dirname($jsonFile);
            if (!empty($json['intents'])) {
                $intentPath = $pluginDir . '/' . $json['intents'];
                $dirs[] = $intentPath;
            }
        }
    }

    return $dirs;
}