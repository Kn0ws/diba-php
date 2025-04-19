<?php

namespace Diba;

use Diba\Request;
use Diba\State;
use Diba\IntentResolver;
use Diba\ExecutorDispatcher;

class JobQueue
{
    protected string $driver;

    public function __construct()
    {
        $config = require __DIR__ . '/../config/queue.php';
        $this->driver = $config['driver'] ?? 'sync';
    }

    public function push(string $event, array $payload = [], ?int $delaySeconds = null): void
    {
        if ($this->driver === 'sync') {
            $this->dispatchNow($event, $payload);
            return;
        }

        if ($this->driver === 'database') {
            $pdo = \Database\ConnectionManager::getPdo();
            $stmt = $pdo->prepare("
                INSERT INTO jobs (event, payload, status, delay_until)
                VALUES (?, ?, 'pending', ?)
            ");
            $delayUntil = $delaySeconds ? date('Y-m-d H:i:s', time() + $delaySeconds) : null;
            $stmt->execute([$event, json_encode($payload, JSON_UNESCAPED_UNICODE), $delayUntil]);
        }
    }



    protected function dispatchNow(string $event, array $payload): void
    {
        $resolver = new IntentResolver(collectIntentDirs());
        $dispatcher = new ExecutorDispatcher();

        foreach ($resolver->getAll() as $intent) {
            $listeners = $intent->getEffectListeners();
            if (($listeners[$event] ?? null) === $intent->name) {
                $request = new Request('EVENT', $intent->name, $payload);
                $state = new State();
                $dispatcher->dispatch($intent, $request, $state);
            }
        }
    }
}
