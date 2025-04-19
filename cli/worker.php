<?php

use Diba\IntentResolver;
use Diba\ExecutorDispatcher;
use Diba\Request;
use Diba\State;

require_once __DIR__ . '/../bootstrap.php';

$pdo = \Database\ConnectionManager::getPdo();
$state = new State();

$state->info("[Worker] Polling Start.");
while (true) {

    $stmt = $pdo->prepare("
        SELECT * FROM jobs
        WHERE status = 'pending'
          AND (delay_until IS NULL OR delay_until <= NOW())
        ORDER BY id ASC
        LIMIT 10
    ");
    $stmt->execute();
    $jobs = $stmt->fetchAll();

    if (empty($jobs)) {
        // $state->debug("[Worker] No Pending Jobs.");
        sleep(5);
        continue;
    }

    foreach ($jobs as $job) {
        $state->info("[Worker] Executing: {$job['event']} (ID={$job['id']})");

        try {
            $resolver = new IntentResolver(collectIntentDirs());
            $dispatcher = new ExecutorDispatcher();
            $request = new Request('EVENT', $job['event'], json_decode($job['payload'], true));

            foreach ($resolver->getAll() as $intent) {
                $listeners = $intent->getEffectListeners();
                if (($listeners[$job['event']] ?? null) === $intent->name) {
                    $dispatcher->dispatch($intent, $request, $state);
                }
            }

            $pdo->prepare("UPDATE jobs SET status = 'done', executed_at = NOW() WHERE id = ?")->execute([$job['id']]);
            $state->info("[Worker] Job Done: ID={$job['id']} event={$job['event']}");

        } catch (\Throwable $e) {
            $retryCount = $job['retry_count'] + 1;
            if ($retryCount > $job['max_retries']) {
                $pdo->prepare("UPDATE jobs SET status = 'failed', failed_at = NOW() WHERE id = ?")
                    ->execute([$job['id']]);
                $state->error("[Worker] Job Failed: ID={$job['id']} event={$job['event']} - " . $e->getMessage());
            } else {
                $pdo->prepare("UPDATE jobs SET retry_count = ? WHERE id = ?")
                    ->execute([$retryCount, $job['id']]);
                $state->debug("[Worker] Retry {$retryCount}/{$job['max_retries']}: ID={$job['id']} event={$job['event']}");
            }
        }
    }
}
