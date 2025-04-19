<?php

namespace Executors;

use Diba\Request;
use Diba\State;

class DummyApiExecutor
{
    public function index(Request $request, State $state): void
    {
        $state->set('data', ['Dummy一覧']);
    }

    public function show(Request $request, State $state): void
    {
        $id = $request->input('id');
        $state->set('data', "Dummy詳細: {$id}");
    }

    public function store(Request $request, State $state): void
    {
        $state->set('message', 'Dummy作成完了');
    }

    public function update(Request $request, State $state): void
    {
        $id = $request->input('id');
        $state->set('message', "Dummy更新: {$id}");
    }

    public function destroy(Request $request, State $state): void
    {
        $id = $request->input('id');
        $state->set('message', "Dummy削除: {$id}");
    }
}
