<?php

namespace Diba\Middlewares;

use Diba\Request;
use Diba\Response;
use Diba\State;
use Diba\Middleware;
use Diba\Responses\Json;

class RequireAuth implements Middleware
{
    public function handle(Request $request, State $state): ?Response
    {
        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) {
            return new Json([
                'error' => 'Unauthorized. Login required.',
            ], 401);
        }

        return null; // 通過OK
    }
}
