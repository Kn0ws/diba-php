<?php

namespace Diba;

class Intent
{
    public string $name;
    public array $config;
    public ?string $plugin = null;

    public function __construct(string $name, array $config)
    {
        $this->name = $name;
        $this->config = $config;
    }

    public function getExecutors(): array
    {
        return $this->config['executors'] ?? [];
    }

    public function getEffects(): array
    {
        return $this->config['effects'] ?? [];
    }

    public function getEffectListeners(): array
    {
        return $this->config['on_effects'] ?? [];
    }

    public function getResponseClass(): string
    {
        return $this->config['response'] ?? \Diba\Responses\Json::class;
    }

    public function generateResponse(State $state): \Diba\Response
    {
        $accept = $_SERVER['HTTP_ACCEPT'] ?? 'application/json';
        if (isset($this->config['responses'])) {
            foreach ($this->config['responses'] as $mime => $class) {
                if (str_contains($accept, $mime)) {
                    $args = $this->config['response_args'][$mime] ?? [];
    
                    if ($class === \Diba\Responses\View::class) {
                        return new $class($args[0] ?? 'default.php', $state->all());
                    }
                    
                    if ($class === \Diba\Responses\Json::class) {
                        return new $class($state->all(), ...$args);
                    }

                    return new $class(...$args);
                }
            }
        }
        
        $class = $this->config['response'] ?? \Diba\Responses\Json::class;
        $args  = $this->config['response_args'] ?? [];
    
        if ($class === \Diba\Responses\View::class) {
            return new $class($args[0] ?? 'default.php', $state->all());
        }
    
        if ($class === \Diba\Responses\Json::class) {
            return new $class($state->all(), ...$args);
        }
    
        return new $class(...$args);
    }
    


}
