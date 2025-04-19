<?php

function stub(string $file, array $vars): string {
    $template = file_get_contents(__DIR__ . "/../stubs/{$file}");
    foreach ($vars as $key => $val) {
        $template = str_replace("{{{$key}}}", $val, $template);
    }
    return $template;
}

function slugify(string $name): string {
    return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $name));
}