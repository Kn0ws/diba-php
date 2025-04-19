<?php

if (!file_exists(__DIR__ . '/../.env')) {
    return;
}

foreach (file(__DIR__ . '/../.env') as $line) {
    if (preg_match('/^([A-Z_]+)=(.*)$/', trim($line), $m)) {
        putenv("{$m[1]}={$m[2]}");
    }
}
