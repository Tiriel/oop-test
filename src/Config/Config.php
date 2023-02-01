<?php

namespace App\Config;

use App\Routing\Route;

class Config
{
    public static function getRoutes(): array
    {
        return [
            new Route(
                'main_index',
                '/',
            ),
            new Route(
                'main_contact',
                '/contact',
            ),
            new Route(
                'post_list',
                '/posts',
            ),
            new Route(
                'post_new',
                '/posts/new',
                methods: ['GET', 'POST']
            ),
            new Route(
                'post_show',
                '/posts/{slug}',
                requirements: ['slug' => '\w+']
            ),
        ];
    }

    public static function getTemplateContext(): array
    {
        return [
            '_contextTitle' => 'Test OOP',
        ];
    }

    public static function getDbConfig(): array
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
