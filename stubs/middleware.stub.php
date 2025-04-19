<?php

namespace Constraints;

use Diba\Request;
use Diba\State;

class {{class}}
{
    public static function check(Request \$request, State \$state): bool
    {
        // TODO: 認証・権限チェックなど
        return true;
    }

    public static function after(Request \$request, State \$state): void
    {
        // TODO: afterミドルウェアが必要な場合はこちらに実装
    }
}
