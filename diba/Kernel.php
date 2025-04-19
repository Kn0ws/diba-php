<?php

namespace Diba;
use Diba\Responses\Json;

class Kernel
{
    public function handle(Request $request, ?string $forcedIntentName = null, ?State $state = null): Response
    {
        $resolver = new IntentResolver(collectIntentDirs());
        $intent = $forcedIntentName
            ? $resolver->resolveByName($forcedIntentName)
            : $resolver->resolve($request);

        if (!$intent) {
            return new Json(['error' => 'Intent not found'], 404);
        }

        $state = $state ?? new State();
        $state->setDefaultContext($request);


        $constraints = $intent->config['constraints'] ?? [];
        $checker = new ConstraintChecker();
        if (!$checker->check($constraints, $request, $state)) {
            return new Json(['error' => $state->get('error', 'Validation failed')], 400);
        }

        $middlewareRunner = new MiddlewareRunner();
        $beforeMiddlewares = $intent->config['middlewares']['before'] ?? [];
        if (!$middlewareRunner->runBefore($beforeMiddlewares, $request, $state)) {
            return new Json(['error' => $state->get('error', 'Middleware before failed')], 403);
        }

        $dispatcher = new ExecutorDispatcher();
        $dispatcher->dispatch($intent, $request, $state);

        foreach ($intent->getEffects() as $event) {
            foreach ($resolver->getAll() as $candidate) {
                $listeners = $candidate->getEffectListeners();
                if (($listeners[$event] ?? null) === $candidate->name) {
                    $dispatcher->dispatch($candidate, $request, $state);
                }
            }
        }

        $state->flushEvents();

        $afterMiddlewares = $intent->config['middlewares']['after'] ?? [];
        $middlewareRunner->runAfter($afterMiddlewares, $request, $state);

        return $intent->generateResponse($state);
    }

    
}
