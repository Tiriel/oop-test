<?php

namespace App\Console\Core;

class ArgvInput implements InputInterface
{
    protected array $argv;
    protected array $arguments;
    protected array $options;

    public function __construct(?array $argv = null)
    {
        $argv ??= $_SERVER['argv'] ?? [];

        array_shift($argv);

        $this->argv = $argv;

        $this->parseArgv();
    }

    public function getArgument(string $key, string $default = ''): string
    {
        return $this->arguments[$key] ?? $default;
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function getOption(string $key, string $default = ''): string
    {
        return $this->options[$key] ?? $default;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    private function parseArgv(): void
    {
        while (null !== $token = array_shift($this->argv)) {
            switch ($token) {
                case str_starts_with($token, '--'):
                    $this->parseLongOption($token);
                    break;
                case str_starts_with($token, '-'):
                    $this->parseShortOption($token);
                    break;
                case (!str_contains($token, ':')):
                    $this->parseArgument($token);
                    break;
            };
        }
    }

    private function parseArgument(mixed $token)
    {
        $this->arguments[] = $token;
    }

    private function parseLongOption(mixed $token)
    {
        $name = substr($token, 2);
        $value = null;

        if (false !== $pos = strpos($name, '=')) {
            [$name, $value] = explode('=', $name);
        }

        $this->options[$name] = $value;
    }

    private function parseShortOption(mixed $token)
    {
        $token = substr($token, 1);
        $this->options[$token[0]] = substr($token, 1) ?? null;
    }
}
