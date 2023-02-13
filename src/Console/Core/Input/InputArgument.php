<?php

namespace App\Console\Core\Input;

class InputArgument implements InputDefinitionInterface
{
    protected ?string $value = null;
    public function __construct(
        protected readonly string $name,
        protected readonly InputArgumentTypes $type = InputArgumentTypes::REQUIRED,
        protected readonly ?string $default = null
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function getType(): InputArgumentTypes
    {
        return $this->type;
    }

    public function __toString(): string
    {
        return $this->value ?? $this->default;
    }
}
