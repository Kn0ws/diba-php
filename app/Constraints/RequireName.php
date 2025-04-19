<?php

namespace Constraints;

use Diba\Request;
use Diba\State;

class RequireName
{
    public static function check(Request $request, State $state): bool
    {
        $name = $request->input('name');
        if (!$name) {
            $state->set('error', '"name" パラメータが必要です');
            return false;
        }

        return true;
    }
}
