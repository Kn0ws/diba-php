<?php

namespace Diba;

use Throwable;

class Log
{
    protected array $config;
    protected string $defaultPath;
    protected array $levelPaths;
    protected string $dateFormat;
    protected array $context = [];
    protected string $env;

    public function __construct()
    {
        $configPath = __DIR__ . '/../config/log.php';

        $this->config = file_exists($configPath) ? require $configPath : [];

        $this->defaultPath = $this->config['default'] ?? 'logs/app';
        $this->levelPaths = $this->config['levels'] ?? [];
        $this->dateFormat = $this->config['date_format'] ?? 'Y-m-d H:i:s';
        $this->env = getenv('APP_ENV') ?: 'production';

        $this->context = $this->getHttpContext();
    }

    public function setContext(array $context = []): void
    {
        $this->context = array_merge($this->context, $context);
    }

    protected function getHttpContext(): array
    {
        if (php_sapi_name() === 'cli') return [];

        return [
            'method' => $_SERVER['REQUEST_METHOD'] ?? '-',
            'uri'    => $_SERVER['REQUEST_URI'] ?? '-',
            'ip'     => $_SERVER['REMOTE_ADDR'] ?? '-',
        ];
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

    public function logException(Throwable $e, string $level = 'error'): void
    {
        $this->setContext();
        $message = $e->getMessage();
        $trace = $e->getTraceAsString();
        $this->write("Exception: $message\nTrace:\n$trace", $level);
    }

    protected function resolvePath(string $level): string
    {
        $isDefinedLevel = array_key_exists($level, $this->levelPaths);
        $baseDir = $isDefinedLevel ? $this->levelPaths[$level] : $this->defaultPath;

        if (!str_starts_with($baseDir, '/')) {
            $baseDir = realpath(__DIR__ . '/../') . '/' . ltrim($baseDir, '/');
        }
        
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
