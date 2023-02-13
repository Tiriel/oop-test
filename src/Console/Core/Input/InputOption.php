<?php

namespace App\Console\Core\Input;

class InputOption implements InputDefinitionInterface
{
    protected ?string $value = null;

    public function __construct(
        protected readonly string $name,
        protected readonly string $shortcut = '',
        protected readonly InputOptionTypes $type = InputOptionTypes::VALUE_OPTIONAL,
        protected readonly ?string $default = null
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getShortcut(): string
    {
        return $this->shortcut;
    }

    public function getValue(): string
    {
        return $this->value ?? $this->default;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function getType(): InputOptionTypes
    {
        return $this->type;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
