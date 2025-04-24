<?php

namespace Diba;

use Diba\EventQueue;
use Diba\Log;
use Diba\Session;

class State
{
    protected array $data = [];
    protected ?Log $logger = null;
    protected EventQueue $events;
    protected ?Session $session = null;

    public function __construct()
    {
        $this->events = new EventQueue();
    }

    public function set(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    public function get(string $key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    public function all(): array
    {
        return $this->data;
    }

    public function emit(string $event, array $payload = []): void
    {
        $this->events->push($event, $payload);
    }

    public function getEvents(): array
    {
        return array_column($this->events->all(), 'event');
    }

    public function flushEvents(): void
    {
        $queue = new \Diba\JobQueue();

        foreach ($this->events->all() as $entry) {
            $event = $entry['event'] ?? null;
            $payload = $entry['payload'] ?? [];

            if (!$event) {
                continue; // 無効なイベントをスキップ
            }

            $queue->push($event, $payload);
        }
    }

    public function log(string $message, string $level = 'info'): void
    {
        if (!$this->logger) {
            $this->logger = new Log();
        }

        $this->logger->write($message, $level);
    }

    public function info(string $message): void
    {
        $this->log($message, 'info');
    }

    public function error(string $message): void
    {
        $this->log($message, 'error');
    }

    public function debug(string $message): void
    {
        $this->log($message, 'debug');
    }

    public function setContext(array $context): void
    {
        if (!$this->logger) {
            $this->logger = new \Diba\Log();
        }
        $this->logger->setContext($context);
    }

    public function setDefaultContext(Request $request): void
    {
        if (!$this->logger) {
            $this->logger = new Log();
        }
        $context = [];
        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        if ($ip) {
            $context['ip'] = $ip;
        }

        if (session_status() === PHP_SESSION_ACTIVE) {
            $context['session_id'] = session_id();
        }

        $this->logger->setContext($context);
    }


    public function csrf(): string
    {
        if (!isset($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }

    public function validateCsrf(?string $token): bool
    {
        return isset($_SESSION['_csrf_token']) && hash_equals($_SESSION['_csrf_token'], $token ?? '');
    }

    public function session(): Session
    {
        if (!$this->session) {
            $this->session = new Session();
        }
        return $this->session;
    }

    public function dump(): array
    {
        return $this->data;
    }

    public function intentName(): ?string
    {
        return $this->get('intent');
    }




}
