<?php

namespace App\Console\Core\Input;

interface InputDefinitionInterface
{
    public function getName(): string;

    public function getValue(): string;

    public function setValue(string $value): void;

    public function getType(): InputArgumentTypes|InputOptionTypes;

    public function __toString(): string;
}
