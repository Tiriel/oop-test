<?php

namespace App;

use App\Config\{Db, Routes, Services, SingletonInterface, Templating};
use App\Controller\BaseController;
use App\Http\{Request, RequestStack};
use App\Db\{Connection, Query\Core\ClassBoundQuery, Query\Core\Query};
use App\Templating\Templater;
use App\Routing\{Router, UrlGenerator, Route};

class Container
{
    private iterable $services = [];

    public function get(string $className, bool $argsOnly = false): mixed
    {
        $arguments = Services::get($className);

        foreach ($arguments as $key => $service) {
            if (is_string($service) && \class_exists($service)) {
                $arguments[$key] = $this->get($service);
            }
        }

        if ($argsOnly) {
            return $arguments;
        }

        return $this->services[$className] ?? $this->services[$className] = new $className(...$arguments);
    }

    public function getController(string $controller): BaseController
    {
        $controllerClass = 'App\\Controller\\' . ucfirst($controller) . 'Controller';

        if (array_key_exists($controllerClass, $this->services)) {
            return $this->services[$controllerClass];
        }

        $arguments = $this->get(BaseController::class, true);
        $arguments[array_search(Query::class, $arguments)+1] = $this->getQuery($controller);

        return $this->services[$controllerClass] = new $controllerClass(...$arguments);
    }

    public function getQuery(string $model): Query
    {
        $queryClass = 'App\\Db\\Query\\' . ucfirst($model) . 'Query';

        if (!\class_exists($queryClass)) {
            return $this->getQuery('post');
        }

        return $this->services[$queryClass]
            ?? $this->services[$queryClass] = ClassBoundQuery::createClassQuery($this->get(Connection::class), $model);
    }

    public static function create(): static
    {
        return new static();
    }
}
