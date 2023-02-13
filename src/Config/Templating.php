<?php

namespace App\Config;

class Templating implements ConfigInterface
{
    protected static array $config = [
        'loader_paths' => [__DIR__ . '/../../templates'],
    ];

    public static function get(string $key = ''): array
    {
        return $key === '' ? static::$config : static::$config[$key];
    }
}
