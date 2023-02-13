<?php

namespace App\Http\Exception;

use App\Routing\Route;

class BadMethodHttpException extends HttpException
{
    public function __construct(Route $route)
    {
        $message = sprintf(
            "Wrong method for request %s (accepted : [%s])",
            $route->getPath(),
            implode(', ', $route->getMethods())
        );

        parent::__construct($message, 405);
    }
}
