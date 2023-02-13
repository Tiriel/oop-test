<?php

namespace App\Console\Core\Output;

interface OutputInterface
{
    public function writeln(string|iterable $message): void;

    public function write(string|iterable $messages, bool $newline = false): void;
}
