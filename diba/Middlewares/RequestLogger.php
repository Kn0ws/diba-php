<?php

namespace Diba\Middlewares;

use Diba\Log;
use Diba\Request;
use Diba\State;

class RequestLogger
{
    public static function handle(Request $request, State $state): void
    {
        $log = new Log();
        $log->info("Intent: {$state->intentName()} Started. Request: {$request->method} {$request->path}");
    }
}
