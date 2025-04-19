<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../bootstrap.php';

use Diba\Kernel;
use Diba\Request;

$request = new Request();
$kernel = new Kernel();
$response = $kernel->handle($request);

$response->send();

