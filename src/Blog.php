<?php

namespace App;

use App\Http\Exception\{BadMethodHttpException,HttpException,NotFoundHttpException};
use App\Http\{Response,Request,RequestStack};
use App\Routing\{Route,Router};

class Blog
{
    private readonly Container $container;

    public function __construct()
    {
        $this->container = Container::create();
    }

    public function handle(Request $request): Response
    {
        $router = $this->container->get(Router::class);
        $this->container->get(RequestStack::class)->push($request);

        /** @var Route $route */
        $route = $router->route($request);
        if ('error' === $controller = $route->getController()) {
            $this->throwHttpException($route);
        }

        $controller = $this->container->getController($controller);
        $action = $route->getAction();

        return $controller->$action(...$request->getAttributes());
    }

    private function throwHttpException(Route $route): void
    {
        throw match ($route->getName()) {
            'error_bad_method' => new BadMethodHttpException($route),
            'error_not_found' => new NotFoundHttpException($route),
            default => new HttpException()
        };
    }
}
