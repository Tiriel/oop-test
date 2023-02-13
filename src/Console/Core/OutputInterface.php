<?php

namespace App\Console\Core;

interface OutputInterface
{
    public function write(string $message): void;

    public function writeln(string $message): void;
}
