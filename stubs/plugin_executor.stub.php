<?php

namespace Plugins\{{plugin}}\Executors;

use Diba\Request;
use Diba\State;

class {{plugin}}Executor
{
    public function handle(Request $request, State $state): void
    {
        $state->set('message', 'Hello from {{plugin}}Executor!');
    }
}
