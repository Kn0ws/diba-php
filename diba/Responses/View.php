<?php
namespace Diba\Responses;

use Diba\Response;

class View extends Response
{
    public function __construct(string $viewFile, array $data = [])
    {
        extract($data);
        ob_start();
        include $viewFile;
        $content = ob_get_clean();

        parent::__construct(200, $content);
        $this->setHeader('Content-Type', 'text/html; charset=utf-8');

    }
}
