<?php

namespace App\Config;

class Db implements ConfigInterface
{
    public static function get(): array
    {
        return [
            'platform' => 'mysql',
            'host' => '127.0.0.1',
            'port' => '3306',
            'user' => 'admin',
            'password' => 'admin',
            'dbName' => 'test_oop',
        ];
    }
}
