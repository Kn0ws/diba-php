<?php

namespace Diba;

class Response
{
    protected int $status;
    protected array $headers = [];
    protected string $content = '';

    public function __construct(int $status = 200, string $content = '')
    {
        $this->status = $status;
        $this->content = $content;
    }

    public function send(): void
    {
        if (php_sapi_name() !== 'cli') {
            http_response_code($this->status);
            foreach ($this->headers as $key => $value) {
                header("{$key}: {$value}");
            }
        }

        echo $this->content;
    }

    public function setHeader(string $key, string $value): void
    {
        $this->headers[$key] = $value;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
