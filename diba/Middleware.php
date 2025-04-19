<?php

namespace Diba;

interface Middleware
{
    public function handle(Request $request, State $state): ?Response;
}
