<?php

namespace App\Routing;

use App\Http\Request;

class Router
{
    public function __construct(
        private readonly iterable $routes
    ) {}

    public function route(Request $request): Route
    {
        foreach ($this->routes as $route) {
            /** @var Route $route */
            if (!$this->match($request, $route)) {
                continue;
            }

            if (!\in_array($request->getMethod(), $route->getMethods() ?? Request::METHODS)) {
                return new Route('error_bad_method',
                    $request->getMethod() . " - " . $request->getPath(),
                    methods: $route->getMethods()
                );
            }

            return $route;
        }

        return new Route('error_not_found', $request->getPath());
    }

    private function match(Request $request, Route $route): bool
    {
        $pathRegex = $this->getPathRegex($route);

        if (preg_match($pathRegex, $request->getPath(), $matches)) {
            $request->setAttributes($matches);

            return true;
        }

        return false;
    }

    private function getPathRegex(Route $route): string
    {
        $regex = $route->getPath();
        $requirements = $route->getRequirements();

        $regex = preg_replace_callback(
            '#{(\w+)}#',
            function ($matches) use ($requirements) {
                $req = $requirements[$matches[1]] ?? '.*';

                return "(?<{$matches[1]}>{$req})";
            },
            $regex
        );

        return "#^{$regex}$#";
    }
}
