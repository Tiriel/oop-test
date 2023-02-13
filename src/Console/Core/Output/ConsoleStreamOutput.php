<?php

namespace App\Console\Core\Output;

use App\Console\Core\Output\OutputInterface;

class ConsoleStreamOutput implements OutputInterface
{
    private $stream;

    public function __construct()
    {
        $this->stream = @fopen('php://STDOUT', 'w');
    }

    public function writeln(string|iterable $message): void
    {
        $this->write($message, true);
    }

    public function write(string|iterable $messages, bool $newline = false): void
    {
        if (!is_iterable($messages)) {
            $messages = [$messages];
        }

        foreach ($messages as $message) {
            $this->doWrite($message, $newline);
        }
    }

    private function doWrite(string $message, bool $newline = false): void
    {
        if ($newline) {
            $message .= \PHP_EOL;
        }

        @fwrite($this->stream, $message);
    }
}
