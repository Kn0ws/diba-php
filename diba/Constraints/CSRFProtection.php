<?php

namespace Diba\Constraints;

use Diba\Request;
use Diba\State;
use Diba\Constraint;

class CSRFProtection implements Constraint
{
    public function check(Request $request, State $state): bool
    {
        $token = $request->input('_token');
        return $state->validateCsrf($token);
    }

    public function message(): string
    {
        return 'CSRF token is invalid.';
    }
}
