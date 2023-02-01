<?php

namespace App\Templating;

use App\Routing\PathUtils;
use App\Templating\Views\Core\AbstractView;
use App\Templating\Views\Core\BaseView;

class Templater
{
    private iterable $context;

    public function __construct(
        private readonly array $configContext
    ) {}

    public function render(string $viewName, iterable &$context = []): string
    {
        $context = array_merge($context, $this->configContext);

        /** @var AbstractView|string $class */
        $class = 'App\\Templating\\Views\\' . PathUtils::pascalizeName($viewName) . 'View';

        if (!\class_exists($class)) {
            return (new class ($this->parser(...), $context) extends BaseView {})->render();
        }

        return $this->parser((new $class($this->parser(...), $context))->render(), $context);
    }

    public function parser($buffer, $context): string|array
    {
        return preg_replace_callback(
            '#{{ (\w+) }}#',
            function ($matches) use ($context) {
                return $context[$matches[1]] ?? '';
            },
            $buffer);
    }
}
