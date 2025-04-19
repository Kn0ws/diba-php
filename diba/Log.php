<?php

namespace Diba;

class Log
{
    protected array $config;
    protected string $defaultPath;
    protected array $levelPaths;
    protected string $dateFormat;
    protected array $context = [];

    public function __construct()
    {
        $configPath = __DIR__ . '/../config/log.php';

        $this->config = file_exists($configPath)
            ? require $configPath
            : [];

        $this->defaultPath = $this->config['default'] ?? 'logs/app';
        $this->levelPaths = $this->config['levels'] ?? [];
        $this->dateFormat = $this->config['date_format'] ?? 'Y-m-d H:i:s';
    }

    public function setContext(array $context): void
    {
        $this->context = $context;
    }

    protected function formatContext(): string
    {
        if (empty($this->context)) return '';
        $parts = [];
        foreach ($this->context as $key => $value) {
            $parts[] = "{$key}={$value}";
        }
        return '[' . implode(', ', $parts) . ']';
    }

    public function write(string $message, string $level = 'info'): void
    {
        $timestamp = date($this->dateFormat);
        $context = $this->formatContext();
        $formatted = "[$timestamp][$level]$context $message" . PHP_EOL;

        $path = $this->resolvePath($level);
        file_put_contents($path, $formatted, FILE_APPEND);

        if (php_sapi_name() === 'cli') {
            echo $formatted;
        }
    }

    protected function resolvePath(string $level): string
    {
        $isDefinedLevel = array_key_exists($level, $this->levelPaths);
        $baseDir = $isDefinedLevel
            ? $this->levelPaths[$level]
            : $this->defaultPath;
    
        if (!is_dir($baseDir)) {
            mkdir($baseDir, 0777, true);
        }
    
        $basename = $isDefinedLevel ? $level : 'app';
    
        $filename = "{$basename}_" . date('Y-m-d') . ".log";
        return rtrim($baseDir, '/') . '/' . $filename;
    }
    public function info(string $message): void
    {
        $this->write($message, 'info');
    }

    public function error(string $message): void
    {
        $this->write($message, 'error');
    }

    public function debug(string $message): void
    {
        $this->write($message, 'debug');
    }
}
