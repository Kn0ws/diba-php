<?php

namespace Diba;

class EventEmitter
{
    protected static array $listeners = [];

    public static function on(string $event, callable $callback): void
    {
        self::$listeners[$event][] = $callback;
    }

    public static function emit(string $event, array $payload = []): void
    {
        foreach (self::$listeners[$event] ?? [] as $listener) {
            $listener($payload);
        }
    }
}
