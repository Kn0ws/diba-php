<?php
namespace Diba\Responses;

use Diba\Response;

class View extends Response
{
    public function __construct(string $template, array $data = [])
    {
        ob_start();
        extract($data);

        $templatePath = realpath(__DIR__ . '/../../app/Views/' . $template);

        if (!$templatePath || !file_exists($templatePath)) {
            throw new \Exception("View file not found: $template");
        }

        include $templatePath;
        $body = ob_get_clean();

        parent::__construct(200, $body);
        $this->setHeader('Content-Type', 'text/html');
    }
}
