<?php

namespace Diba;

class EventQueue
{
    protected array $queue = [];

    public function push(string $event, array $payload = []): void
    {
        $this->queue[] = [
            'event' => $event,
            'payload' => $payload
        ];
    }


    public function all(): array
    {
        return $this->queue;
    }

    public function flush(): void
    {
        $this->queue = [];
    }

}
