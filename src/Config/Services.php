<?php

namespace App\Config;

use App\Console\Command\AppTestCommand;
use App\Controller\BaseController;
use App\Db\Connection;
use App\Db\Query\Core\Query;
use App\Http\RequestStack;
use App\Routing\Router;
use App\Routing\UrlGenerator;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Services implements ConfigInterface
{
    protected static array $definitions = [];

    public static function get(string $className = ''): array
    {
        if (empty(static::$definitions)) {
            static::initDefinitions();
        }

        if (!array_key_exists($className, static::$definitions)) {
            throw new \RuntimeException(sprintf("The requested service %s is not defined for Container use.", $className));
        }

        return static::$definitions[$className];
    }

    public static function initDefinitions(): void
    {
        static::$definitions = [
            BaseController::class => [Environment::class, Query::class],
            RequestStack::class => [],
            Router::class => [Routes::get()],
            UrlGenerator::class => [],
            Environment::class => [FilesystemLoader::class],
            FilesystemLoader::class => [Templating::get('loader_paths')],
            Connection::class => [Db::get()],
            Query::class => [Connection::class],
            AppTestCommand::class => [],
        ];
    }
}
