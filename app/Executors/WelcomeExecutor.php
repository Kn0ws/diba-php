<?php

namespace Executors;

use Diba\Request;
use Diba\State;
use Diba\Response;

class WelcomeExecutor
{
    public function sayWelcome(Request $request, State $state): void
    {
        $state->set('message', 'Welcome to DIBA PHP!');
        $state->set('time', date('Y-m-d H:i:s'));
    }

    public function sayWelcomeHtml(Request $request, State $state): void
    {
        $state->set('html', '<h1>Welcome to DIBA PHP! (HTML)</h1><p>This is rendered as HTML.</p>');
    }

    public function redirect(Request $request, State $state): void
    {
        $state->set('redirect', '/welcome');
    }

    public function sayWelcomeRequire(Request $request, State $state): void
    {
        $name = $request->input('name') ?? 'Guest';
        $state->set('message', "{$name}, Welcome to DIBA PHP!");
        $state->set('name', $name);
        $state->set('time', date('Y-m-d H:i:s'));
    }

    public function sayWelcomeView(Request $request, State $state): void
    {
        $name = $request->input('name') ?? 'Guest';
        $state->set('title', "DIBA PHP");
        $state->set('data', [
            'message' => "{$name}, Welcome to DIBA PHP!",
            'time' => date('Y-m-d H:i:s'),
        ]);
    }

    public function sayWelcomeEvent(Request $request, State $state): void
    {
        $state->set('message_event', 'イベント発火 Welcome Event');
        $state->emit('welcome.sayWelcomeEmit');
    }

    public function sayWelcomeEventEmit(Request $request, State $state): void
    {
        $state->set('message_event_emit', 'イベントを受信しました Welcome Event Effect');
    }

    public function sayWelcomeWithId(Request $request, State $state): void
    {
        $id = $request->param('id');
        $state->set('message', "ID {$id} にようこそ！");
    }

    public function sayWelcomeSmart(Request $request, State $state): \Diba\Response
    {
        $accept = $request->header('Accept') ?? '';

        if (str_contains($accept, 'text/html')) {
            return new \Diba\Responses\Html('<h1>Welcome to DIBA PHP!</h1><p>This is rendered as HTML.</p>');
        }

        return new \Diba\Responses\Json([
            'message' => 'Welcome to DIBA PHP!',
            'time' => date('Y-m-d H:i:s'),
        ]);
    }

    public function sayWelcomeWithFilter(Request $request, State $state): void
    {
        $state->set('message', 'ようこそ！認証と権限チェック済みです');
    }
}
