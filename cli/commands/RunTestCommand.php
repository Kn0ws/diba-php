<?php

namespace Cli\Commands;

use Diba\Request;
use Diba\Kernel;
use Symfony\Component\Yaml\Yaml;

class RunTestCommand implements CommandInterface
{
    public static function name(): string
    {
        return 'run:test';
    }

    public static function description(): string
    {
        return 'Run a single test file (YAML)';
    }

    public static function handle(array $argv): void
    {
        $file = $argv[2] ?? null;
        if (!$file || !file_exists($file)) {
            echo "Usage: run:test <test.yaml>\n";
            return;
        }

        $yaml = Yaml::parseFile($file);
        $intent = $yaml['intent'] ?? null;
        $input = $yaml['input'] ?? [];
        $expect = $yaml['expect'] ?? $yaml['expected'] ?? [];

        $expectedStatus = $expect['status'] ?? 200;
        $expectedBody = $expect['body'] ?? (is_array($expect) ? $expect : []);

        if (!$intent) {
            echo " intent が指定されていません\n";
            return;
        }

        $request = new Request('GET', '/' . strtolower($intent), $input);
        $kernel = new Kernel();
        $response = $kernel->handle($request, $intent);

        $statusOk = $expectedStatus == $response->getStatus();
        $bodyOk = $expectedBody == json_decode($response->getContent(), true);

        if (!$statusOk) {
            echo "- Expected status: {$expectedStatus}, got: {$response->getStatus()}\n";
        }
        if (!$bodyOk) {
            echo "- Body mismatch\n";
            echo "- Expected body:\n";
            print_r($expectedBody);
            echo "- Actual body:\n";
            print_r(json_decode($response->getContent(), true));
        }
    }
}
