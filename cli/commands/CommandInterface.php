<?php

namespace Cli\Commands;

interface CommandInterface
{
    public static function name(): string;
    public static function description(): string;
    public static function handle(array $argv): void;
}
