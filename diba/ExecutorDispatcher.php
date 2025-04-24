<?php

namespace Diba;

class ExecutorDispatcher
{
    /**
     * $intentインスタンスを受け取り、
     * $intent->getExecutors() を使って executor を抽出して実行
     *
     * @param Intent $intent
     * @param Request $request
     * @param State $state
     * @return void
     */
    public function dispatch(Intent $intent, Request $request, State $state): void
    {
        foreach ($intent->getExecutors() as $execDef) {
            if (!str_contains($execDef, '@')) {
                throw new \Exception("Invalid executor format: {$execDef}");
            }

            [$class, $method] = explode('@', $execDef);
            $fqcn = str_contains($class, '\\') ? ltrim($class, '\\') : '\\App\\Executors\\' . $class;

            if (!class_exists($fqcn)) {
                throw new \Exception("Executor class not found: {$fqcn}");
            }

            if (!method_exists($fqcn, $method)) {
                throw new \Exception("Method {$method} not found in executor {$fqcn}");
            }

            $executor = new $fqcn();

            $state->setContext([
                'intent' => $intent->name,
                'executor' => class_basename($fqcn),
                'method' => $method,
                'event' => $request->method === 'EVENT' ? $request->path : null,
            ]);

            $executor->$method($request, $state);
        }
    }


    /**
     * $executorListを受け取り、各executorを実行
     * before: や after:、 on_effects: の内部処理などに使用
     * 
     * @param array $executorList
     * @param Request $request
     * @param State $state
     * @return void
     */
    public function dispatchList(array $executorList, Request $request, State $state): void
    {
        foreach ($executorList as $exec) {
            if (!str_contains($exec, '@')) {
                throw new \Exception("Invalid executor format: {$exec}");
            }

            [$class, $method] = explode('@', $exec);
            $fqcn = str_contains($class, '\\') ? ltrim($class, '\\') : '\\Executors\\' . $class;

            if (!class_exists($fqcn)) {
                throw new \Exception("Executor class not found: {$fqcn}");
            }

            if (!method_exists($fqcn, $method)) {
                throw new \Exception("Method {$method} not found in executor {$fqcn}");
            }

            $instance = new $fqcn();
            
            $state->setContext([
                'executor' => class_basename($fqcn),
                'method' => $method,
                'event' => $request->method === 'EVENT' ? $request->path : null,
            ]);

            $instance->$method($request, $state);
        }
    }


    
}
