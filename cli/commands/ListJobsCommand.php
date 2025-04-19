<?php

namespace Cli\Commands;

class ListJobsCommand implements CommandInterface
{
    public static function name(): string
    {
        return 'list:jobs';
    }

    public static function description(): string
    {
        return 'Show recent jobs in the queue';
    }

    public static function handle(array $argv): void
    {
        $pdo = \Database\ConnectionManager::getPdo();
        $stmt = $pdo->query("SELECT * FROM jobs ORDER BY id DESC LIMIT 20");
        $jobs = $stmt->fetchAll();

        echo "+----+----------------+-----------+--------+---------------------+---------------------+\n";
        echo "| ID | Event          | Status    | Retry  | Delay Until         | Executed At         |\n";
        echo "+----+----------------+-----------+--------+---------------------+---------------------+\n";

        foreach ($jobs as $j) {
            $id = str_pad($j['id'], 2, ' ', STR_PAD_LEFT);
            $event = str_pad($j['event'], 14);
            $status = str_pad($j['status'], 9);
            $retry = "{$j['retry_count']} / {$j['max_retries']}";
            $delay = $j['delay_until'] ?? 'NULL';
            $exec  = $j['executed_at'] ?? 'NULL';

            printf("| %2d | %-14s | %-9s | %-6s | %-19s | %-19s |\n",
                $id, $event, $status, $retry, $delay, $exec
            );
        }

        echo "+----+----------------+-----------+--------+---------------------+---------------------+\n";
    }
}