<?php

namespace Diba;

use Symfony\Component\Yaml\Yaml;

class IntentResolver
{
    protected array $intents = [];

    protected array $typePatterns = [];
    
    public function __construct(array|string $intentDirs)
    {
        $dirs = is_array($intentDirs) ? $intentDirs : [$intentDirs];

        foreach ($dirs as $dir) {
            foreach ($this->findIntentFiles($dir) as $file) {
                $intents = str_ends_with($file, '.json')
                    ? json_decode(file_get_contents($file), true)
                    : Yaml::parseFile($file);
        
                if (!is_array($intents)) {
                    continue;
                }

                $globalMiddlewares = file_exists(__DIR__ . '/../config/middlewares.php')
                    ? require __DIR__ . '/../config/middlewares.php'
                    : ['before' => [], 'after' => []];
        
                foreach ($intents as $name => $config) {
                    $config['middlewares']['before'] = array_merge(
                        $globalMiddlewares['before'],
                        $config['middlewares']['before'] ?? []
                    );
                    $config['middlewares']['after'] = array_merge(
                        $globalMiddlewares['after'],
                        $config['middlewares']['after'] ?? []
                    );

                    $intent = new Intent($name, $config);
                    $pluginName = $this->detectPluginName($file);
                    if ($pluginName) {
                        $intent->plugin = $pluginName;
                    }
                
                    $this->intents[] = $intent;
                }
            }
        }
        
    }



    public function resolve(Request $request): ?Intent
    {
        $this->typePatterns = require __DIR__ . '/../config/route_types.php';

        foreach ($this->intents as $intent) {
            $match = $intent->config['match'] ?? [];
            $intentMethod = strtoupper($match['method'] ?? 'GET');
            $intentPath = $match['path'] ?? null;

            if (!$intentPath || $intentMethod !== strtoupper($request->method)) {
                continue;
            }

            if (preg_match_all('/\{(\w+)\}/', $intentPath, $matches)) {
                $regex = $intentPath;
                foreach ($matches[1] as $key) {
                    $type = $match['where'][$key] ?? '[^/]+';

                    if (isset($this->typePatterns[$type])) {
                        $constraint = $this->typePatterns[$type];
                    } else {
                        $constraint = $type;
                    }
                    $regex = str_replace('{' . $key . '}', "($constraint)", $regex);
                }
            
                $pattern = '#^' . $regex . '$#';
            
                if (preg_match($pattern, $request->path, $values)) {
                    array_shift($values);
                    $params = array_combine($matches[1], $values);
                    $request->setParams($params);
                    return $intent;
                }
            }
            
            if ($intentPath === $request->path) {
                return $intent;
            }
        }

        return null;
    }


    public function resolveByName(string $name): ?Intent
    {
        foreach ($this->intents as $intent) {
            if ($intent->name === $name) {
                return $intent;
            }
        }
        return null;
    }

    public function getAll(): array
    {
        return $this->intents;
    }

    protected function findIntentFiles(string $dir): array
    {
        if (!is_dir($dir)) {
            return [];
        }
    
        $files = [];
    
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS)
        );
    
        foreach ($iterator as $file) {
            if ($file->isFile() && preg_match('/\.(yaml|yml)$/', $file->getFilename())) {
                $files[] = $file->getPathname();
            }
        }
    
        return $files;
    }
    
    protected function detectPluginName(string $filePath): ?string
    {
        if (preg_match('#plugins/([^/]+)/Intents/#', $filePath, $m)) {
            return $m[1];
        }
        return null;
    }

    
}
