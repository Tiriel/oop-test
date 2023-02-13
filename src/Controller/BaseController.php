<?php

namespace App\Controller;

use App\Db\Query\Core\ClassBoundQuery;
use App\Db\Query\Core\Query;
use App\Http\Response;
use App\Templating\Templater;
use Twig\Environment;

abstract class BaseController
{
    public function __construct(
        protected readonly Environment $twig,
        protected readonly Query|ClassBoundQuery $query
    ) {}

    protected function render(string $viewName, iterable $context = [], int $statusCode = 200): Response
    {
        return new Response(
            $this->twig->render($viewName, $context),
            $statusCode
        );
    }
}
