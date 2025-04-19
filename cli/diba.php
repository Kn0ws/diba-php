#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/utils.php';

use Cli\Commands\CommandInterface;

$commands = [
    Cli\Commands\MakeIntentCommand::name() => Cli\Commands\MakeIntentCommand::class,
    Cli\Commands\MakeExecutorCommand::name() => Cli\Commands\MakeExecutorCommand::class,
    Cli\Commands\MakeResponseCommand::name() => Cli\Commands\MakeResponseCommand::class,
    Cli\Commands\MakeConstraintCommand::name() => Cli\Commands\MakeConstraintCommand::class,
    Cli\Commands\MakeModelCommand::name() => Cli\Commands\MakeModelCommand::class,
    Cli\Commands\ListIntentsCommand::name() => Cli\Commands\ListIntentsCommand::class,
    Cli\Commands\ListRoutesCommand::name() => Cli\Commands\ListRoutesCommand::class,
    Cli\Commands\SimulateCommand::name() => Cli\Commands\SimulateCommand::class,
    Cli\Commands\RunTestCommand::name() => Cli\Commands\RunTestCommand::class,
    Cli\Commands\RunTestsCommand::name() => Cli\Commands\RunTestsCommand::class,
    Cli\Commands\MakeServiceCommand::name() => Cli\Commands\MakeServiceCommand::class,
    Cli\Commands\GraphIntentsCommand::name() => Cli\Commands\GraphIntentsCommand::class,
    Cli\Commands\ValidateIntentsCommand::name() => Cli\Commands\ValidateIntentsCommand::class,
    Cli\Commands\MakeRestCommand::name() => Cli\Commands\MakeRestCommand::class,
    Cli\Commands\RunQueueCommand::name() => Cli\Commands\RunQueueCommand::class,
    Cli\Commands\MakeJobCommand::name() => Cli\Commands\MakeJobCommand::class,
    Cli\Commands\ListJobsCommand::name() => Cli\Commands\ListJobsCommand::class,
    Cli\Commands\MakePluginCommand::name() => Cli\Commands\MakePluginCommand::class,
    Cli\Commands\MakeMiddlewareCommand::name() => Cli\Commands\MakeMiddlewareCommand::class,
    Cli\Commands\GenerateDocsCommand::name() => Cli\Commands\GenerateDocsCommand::class,
];

$argv = $_SERVER['argv'] ?? [];
$command = $argv[1] ?? null;

if (!$command || !isset($commands[$command])) {
    echo "DIBA CLI\n";
    echo "Usage:\n";
    foreach ($commands as $name => $class) {
        /** @var CommandInterface $class */
        echo "  {$name}  # {$class::description()}\n";
    }
    exit;
}

$class = $commands[$command];
$class::handle($argv);
