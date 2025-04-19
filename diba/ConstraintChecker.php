<?php

namespace Diba;

class ConstraintChecker
{
    public function check(array $constraints, Request $request, State $state): bool
    {
        foreach ($constraints as $name) {
            $fqcn = '\\Constraints\\' . $name;

            if (!class_exists($fqcn)) {
                $state->set('error', "Constraint not found: {$fqcn}");
                return false;
            }

            if (!method_exists($fqcn, 'check')) {
                $state->set('error', "Constraint class {$fqcn} must have static check() method");
                return false;
            }

            if (!$fqcn::check($request, $state)) {
                return false;
            }
        }

        return true;
    }
}
