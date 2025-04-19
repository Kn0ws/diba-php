<?php

namespace Diba\Responses;

use Diba\Response;

class Json extends Response
{
    public function __construct(array $data = [], int $status = 200)
    {
        parent::__construct($status, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        $this->setHeader('Content-Type', 'application/json');
    }
}
