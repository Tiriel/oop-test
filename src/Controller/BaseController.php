<?php

namespace App\Controller;

use App\Db\Query\Core\ClassBoundQuery;
use App\Db\Query\Core\Query;
use App\Http\Response;
use App\Templating\Templater;

abstract class BaseController
{
    public function __construct(
        protected readonly Templater $templater,
        protected readonly Query|ClassBoundQuery $query
    ) {}

    protected function render(string $viewName, iterable $context = [], int $statusCode = 200): Response
    {
        return new Response(
            $this->templater->render($viewName, $context),
            $statusCode
        );
    }
}
