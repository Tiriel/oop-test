<?php

namespace App\Console\Core\Input;

interface InputInterface
{
    public function getCommandName(): string;
    public function getArgument(string $key, string $default = ''): string;

    public function getArguments(): iterable;

    public function getOption(string $key, string $default = ''): string;

    public function getOptions(): iterable;

    public function parse(): void;
}
