<?php

namespace App\Console\Core\Input;

use App\Console\Application;
use App\Console\Core\Input\InputInterface;

class ArgvInput implements InputInterface
{
    protected array $argv;
    protected array $arguments = [];
    protected array $options = [];

    protected ?string $commandName = null;

    /** @var iterable|array|InputArgument[] */
    protected iterable $argumentDefinitions = [];

    /** @var iterable|array|InputOption[] */
    protected iterable $optionDefinitions = [];

    /** @var iterable|array|InputOption[] */
    protected iterable $shortOptions = [];

    public function __construct(?array $argv = null)
    {
        $argv ??= $_SERVER['argv'] ?? [];

        array_shift($argv);
        $this->commandName = array_shift($argv);

        $this->argv = $argv;
    }

    public function getCommandName(): string
    {
        return $this->commandName;
    }

    public function getArgument(string $key, string $default = ''): string
    {
        foreach ($this->argumentDefinitions as $definition) {
            if ($key === $definition->getName()) {
                return $definition->getValue();
            }
        }

        return '';
    }

    public function getArguments(): iterable
    {
        foreach ($this->argumentDefinitions as $definition) {
            yield $definition->getName() => $definition->getValue();
        }
    }

    public function getOption(string $key, string $default = ''): string
    {
        return $this->options[$key] ?? $default;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function parse(): void
    {
        $tokens = $this->argv;
        while (null !== $token = array_shift($tokens)) {
            switch ($token) {
                case str_starts_with($token, '--'):
                    $this->parseLongOption($token);
                    break;
                case str_starts_with($token, '-'):
                    $this->parseShortOption($token);
                    break;
                default:
                    $this->parseArgument($token);
                    break;
            };
        }

        $received = $this->arguments;
        $definitions = $this->argumentDefinitions;
        $missingArguments = array_filter(array_keys($this->argumentDefinitions), function ($argument) use ($received, $definitions) {
            return !array_key_exists($argument, $received) && InputArgumentTypes::REQUIRED === $definitions[$argument]->getType();
        });

        if (\count($missingArguments) > 0) {
            throw new \BadMethodCallException(sprintf("Not enough arguments, missing : %s", implode(', ', $missingArguments)));
        }
    }

    public function loadDefinitions(array $definitions): void
    {
        foreach ($definitions as $definition) {
            if ($definition instanceof InputArgument) {
                $this->argumentDefinitions[] = $definition;
                continue;
            }
            /** @var InputOption $definition */
            $this->optionDefinitions[$definition->getName()] = $definition;
            if ('' !== $definition->getShortcut()) {
                $this->shortOptions[$definition->getShortcut()] = $definition;
            }
        }
    }

    private function parseArgument(mixed $token)
    {
        $key = \count($this->arguments);
        if (!\array_key_exists($key, $this->argumentDefinitions)) {
            throw new \InvalidArgumentException(sprintf("No argument expected, received \"%s\"", $token));
        }

        $def = $this->argumentDefinitions[$key];
        $def->setValue($token);
        $this->arguments[$key] = $token;
    }

    private function parseLongOption(mixed $token)
    {
        $name = substr($token, 2);
        $value = null;

        if (false !== $pos = strpos($name, '=')) {
            [$name, $value] = explode('=', $name);
        }

        if (!\array_key_exists($name, $this->optionDefinitions)) {
            throw new \InvalidArgumentException(sprintf("Option \"%s\" does not exist.", $token));
        }

        $def = $this->optionDefinitions[$name];
        if ($value === null && $def->getType() === InputOptionTypes::VALUE_REQUIRED) {
            throw new \InvalidArgumentException(sprintf("Option %s requires a value, none given.", $name));
        } elseif ($value && $def->getType() === InputOptionTypes::NO_VALUE) {
            throw new \InvalidArgumentException(sprintf("Option %s does not accept a value, \"%s\" given.", $name, $value));
        }

        $def->setValue($value);
        $this->options[$name] = $value;
    }

    private function parseShortOption(mixed $token)
    {
        $token = substr($token, 1);
        $shortcut = $token[0];
        $value = substr($token, 1) ?? null;

        if (!\array_key_exists($shortcut, $this->shortOptions)) {
            throw new \InvalidArgumentException(sprintf("Option \"%s\" does not exist.", $token));
        }

        $def = $this->shortOptions[$shortcut];
        if ($value === null && $def->getType() === InputOptionTypes::VALUE_REQUIRED) {
            throw new \InvalidArgumentException(sprintf("Option %s requires a value, none given.", $shortcut));
        } elseif ($value && $def->getType() === InputOptionTypes::NO_VALUE) {
            throw new \InvalidArgumentException(sprintf("Option %s does not accept a value, \"%s\" given.", $shortcut, $value));
        }

        $def->setValue($value);
        $this->options[$shortcut] = $value;
    }
}
