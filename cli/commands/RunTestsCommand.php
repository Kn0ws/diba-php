<?php

namespace Cli\Commands;

use Diba\Request;
use Diba\Kernel;
use Symfony\Component\Yaml\Yaml;

class RunTestsCommand implements CommandInterface
{
    public static function name(): string
    {
        return 'run:tests';
    }

    public static function description(): string
    {
        return 'Run all test YAML files in a directory';
    }

    public static function handle(array $argv): void
    {
        $dir = rtrim($argv[2] ?? '', '/');
        if (!is_dir($dir)) {
            echo "Usage: run:tests <directory>\n";
            return;
        }

        $files = glob($dir . '/*.test.yaml');
        $passed = 0;
        $failed = 0;

        foreach ($files as $file) {
            $yaml = Yaml::parseFile($file);
            $intent = $yaml['intent'] ?? null;
            $input = $yaml['input'] ?? [];
            $expect = $yaml['expect'] ?? $yaml['expected'] ?? [];

            $expectedStatus = $expect['status'] ?? 200;
            $expectedBody = $expect['body'] ?? (is_array($expect) ? $expect : []);
            $name = $yaml['test_name'] ?? basename($file);

            if (!$intent) {
                echo "[✗] {$name} → intent not defined\n";
                $failed++;
                continue;
            }

            $request = new Request('GET', '/' . strtolower($intent), $input);
            $kernel = new Kernel();
            $response = $kernel->handle($request, $intent);

            $statusOk = $expectedStatus == $response->getStatus();
            $bodyOk = $expectedBody == json_decode($response->getContent(), true);

            if ($statusOk && $bodyOk) {
                echo "[✓] {$name} passed\n";
                $passed++;
            } else {
                echo "[✗] {$name} failed\n";
                if (!$statusOk) echo " - Expected: {$expectedStatus}, Got: {$response->getStatus()}\n";
                if (!$bodyOk) {
                    echo " - Body mismatch\n";
                    echo " - Expected:\n";
                    print_r($expectedBody);
                    echo " - Actual:\n";
                    print_r(json_decode($response->getContent(), true));
                }
                $failed++;
            }
        }

        echo "-------------------------------\n";
        echo "[✓] {$passed} passed / [✗] {$failed} failed\n";
    }
}
