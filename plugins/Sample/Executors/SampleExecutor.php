<?php

namespace Plugins\Sample\Executors;

use Diba\Request;
use Diba\State;

class SampleExecutor
{
    public function handle(Request $request, State $state): void
    {
        $state->set('message', 'Hello from SampleExecutor!');
    }
}
