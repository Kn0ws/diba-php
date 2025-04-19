<?php

namespace Executors;

use Diba\Request;
use Diba\State;

class {{class}}
{
    public function handle(Request $request, State $state): void
    {
        $state->set('message', '{{class}} executed successfully.');
    }
}
