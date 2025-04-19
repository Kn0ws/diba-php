<?php

namespace Constraints;

use Diba\Request;
use Diba\State;

class {{class}}
{
    public static function check(Request $request, State $state): bool
    {
        if (!$request->input('name')) {
            $state->set('error', '"name" is required.');
            return false;
        }
        return true;
    }
}
