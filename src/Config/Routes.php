<?php

namespace App\Config;

use App\Routing\Route;

class Routes implements ConfigInterface
{
    public static function get(): array
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
}
