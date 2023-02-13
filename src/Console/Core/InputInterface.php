<?php

namespace App\Console\Core;

interface InputInterface
{
    public function getArgument(string $key, string $default = ''): string;

    public function getArguments(): array;

    public function getOption(string $key, string $default = ''): string;

    public function getOptions(): array;
}
