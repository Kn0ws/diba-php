<?php

namespace Diba;

class MiddlewareRunner
{
    public function runBefore(array $middlewares, Request $request, State $state): bool
    {
        foreach ($middlewares as $middleware) {
            $fqcn = ltrim($middleware, '\\');

            if (!class_exists($fqcn)) {
                $state->log("Middleware class not found: {$fqcn}", 'error');
                continue;
            }

            $instance = new $fqcn();

            if (method_exists($instance, 'check')) {
                if (!$instance->check($request, $state)) {
                    $state->log("Constraint failed: {$fqcn}", 'warn');
                    return false;
                }
            }
            elseif (method_exists($instance, 'handle')) {
                $response = $instance->handle($request, $state);
                if ($response !== null) {
                    $state->log("Middleware halted: {$fqcn}", 'info');
                    $response->send();
                    return false;
                }
            } else {
                $state->log("Invalid middleware structure: {$fqcn}", 'error');
                continue;
            }
        }

        return true;
    }

    public function runAfter(array $middlewares, Request $request, State $state): void
    {
        foreach ($middlewares as $middleware) {
            $fqcn = ltrim($middleware, '\\');

            if (!class_exists($fqcn)) {
                $state->log("Middleware class not found: {$fqcn}", 'error');
                continue;
            }

            if (method_exists($fqcn, 'after')) {
                $fqcn::after($request, $state);
            }
        }
    }
}
