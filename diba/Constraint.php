<?php

namespace Diba;

interface Constraint
{
    public function check(Request $request, State $state): bool;

    public function message(): string;
}
