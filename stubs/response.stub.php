<?php

namespace Responses;

use Diba\Response;

class {{class}} extends Response
{
    public function __construct(array $data = [], int $status = 200)
    {
        parent::__construct($status, $data);
    }

    public function send(): void
    {
        header('Content-Type: application/json');
        echo json_encode($this->data);
    }
}
