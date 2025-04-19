<?php

namespace Diba\Responses;

use Diba\Response;

class Redirect extends Response
{
    protected string $location;

    public function __construct(string $url, int $status = 302)
    {
        parent::__construct($status);
        $this->location = $url;
        $this->setHeader('Location', $url);
    }

    public function send(): void
    {
        if (php_sapi_name() !== 'cli') {
            http_response_code($this->status);
            foreach ($this->headers as $key => $value) {
                header("{$key}: {$value}");
            }

            // HTML fallback（ブラウザ外アクセス時の案内）
            echo "<html><body><h1>Redirecting...</h1><p>If not redirected, <a href=\"{$this->location}\">click here</a>.</p></body></html>";
        }
    }
}
