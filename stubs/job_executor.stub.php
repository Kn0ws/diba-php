<?php

namespace Executors;

use Diba\Request;
use Diba\State;

class DummyJobExecutor
{
    public function handle(Request $request, State $state): void
    {
        $name = $request->input('name') ?? 'Guest';
        $state->log('info', "DummyJobExecutor executed for {$name}");
    }
}
