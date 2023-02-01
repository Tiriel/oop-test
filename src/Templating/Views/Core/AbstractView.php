<?php

namespace App\Templating\Views\Core;

abstract class AbstractView
{
    public function __construct(
        protected \Closure $parser,
        protected iterable &$context
    ) {}

    public function render(): string
    {
        $context = $this->getContext();
        foreach ($context as $key => $item) {
            $context[$key] = ($this->parser)($item, $context);
        }
        $content = ($this->parser)($this->getContent(), $context);

        ob_start();
        echo $content;

        return ob_get_clean();
    }

    protected function getContext(): array
    {
        $this->context = $this->context instanceof \Traversable ? iterator_to_array($this->context) : $this->context;

        return $this->context = array_merge($this->doGetContext(), $this->context);
    }

    abstract protected function getContent(): string;

    abstract protected function doGetContext(): array;
}
