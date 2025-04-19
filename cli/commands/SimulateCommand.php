<?php

namespace Cli\Commands;

use Diba\Request;
use Diba\Kernel;
use Diba\State;
use Symfony\Component\Yaml\Yaml;

class SimulateCommand implements CommandInterface
{
    public static function name(): string
    {
        return 'simulate';
    }

    public static function description(): string
    {
        return 'Simulate an intent with input (optional --save and --dump)';
    }

    public static function handle(array $argv): void
    {
        if (!isset($argv[2], $argv[3])) {
            echo "Usage: php diba.php simulate IntentName '{\"key\":\"value\"}' [--save] [--dump]\n";
            exit(1);
        }

        $intentName = $argv[2];
        $inputJson = $argv[3];
        $saveTest = in_array('--save', $argv, true);
        $dumpState = in_array('--dump', $argv, true);

        $input = json_decode($inputJson, true);
        if (!is_array($input)) {
            echo "Invalid JSON input.\n";
            exit(1);
        }

        $request = new Request('SIMULATE', $intentName, $input);
        $state = new State();
        $kernel = new Kernel();
        $response = $kernel->handle($request, $intentName, $state);
        $output = $response->getContent();

        // 出力
        echo $output . "\n";

        // ログ出力
        $log = "[" . date('Y-m-d H:i:s') . "] simulate {$intentName}\n";
        $log .= "Input: {$inputJson}\n";
        $log .= "Output: {$output}\n---\n";
        file_put_contents(__DIR__ . '/../../logs/simulate.log', $log, FILE_APPEND);

        if ($saveTest) {
            $testYaml = [
                'intent' => $intentName,
                'input' => $input,
                'expected' => json_decode($output, true),
            ];

            if ($dumpState) {
                $testYaml['state'] = $state->dump();
            }

            $filename = strtolower($intentName) . '.test.yaml';
            $filepath = __DIR__ . "/../../tests/{$filename}";

            $yaml = Yaml::dump($testYaml, 4, 2);
            file_put_contents($filepath, $yaml);
            echo "Test saved to tests/{$filename}\n";
        }
    }
}
