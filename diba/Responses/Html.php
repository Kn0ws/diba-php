<?php
namespace Diba\Responses;

use Diba\Response;

class Html extends Response
{
    public function __construct(string $html, int $status = 200)
    {
        parent::__construct($status, $html);
        $this->setHeader('Content-Type', 'text/html; charset=utf-8');

    }
}
