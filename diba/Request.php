<?php

namespace Diba;

class Request
{
    public string $path;
    public string $method;
    public array $body;
    protected array $params = [];

    public function __construct(?string $method = null, ?string $path = null, ?array $body = null)
    {
        $this->method = strtoupper($method ?? ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
        $this->path = $path ?? parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $this->path = rtrim($this->path, '/');

        switch ($this->method) {
            case 'GET':
                $this->body = $_GET;
                break;

            case 'POST':
                $this->body = $_POST ?: json_decode(file_get_contents('php://input'), true) ?? [];
                break;

            case 'PUT':
            case 'PATCH':
            case 'DELETE':
                $this->body = json_decode(file_get_contents('php://input'), true) ?? [];
                break;

            default:
                $this->body = $body ?? [];
                break;
        }
    }

    public function input(string $key, $default = null)
    {
        return $this->body[$key] ?? $default;
    }

    public function all(): array
    {
        return $this->body;
    }

    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    public function params(): array
    {
        return $this->params;
    }

    public function param(string $key, $default = null): mixed
    {
        return $this->params[$key] ?? $default;
    }

    public function header(string $name): ?string
    {
        return $_SERVER["HTTP_" . strtoupper(str_replace('-', '_', $name))] ?? null;
    }

}
