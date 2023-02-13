<?php

namespace App\Console\Command;

use App\Console\Core\Input\InputArgument;
use App\Console\Core\Input\InputArgumentTypes;
use App\Console\Core\Input\InputInterface;
use App\Console\Core\Input\InputOption;
use App\Console\Core\Input\InputOptionTypes;
use App\Console\Core\Output\OutputInterface;

abstract class Command
{
    protected array $definitions = [];

    public function __construct()
    {
        $this->initialize();
    }

    public function getDefinitions(): array
    {
        return $this->definitions;
    }

    protected function initialize(): void
    {
    }

    abstract public function execute(InputInterface $input, OutputInterface $output): int;

    protected function addArgument(string $name, InputArgumentTypes $type = InputArgumentTypes::REQUIRED, string $default = ''): void
    {
        $this->definitions[] = new InputArgument($name, $type, $default);
    }

    protected function addOption(string $name, string $shortcut = '', InputOptionTypes $type = InputOptionTypes::VALUE_OPTIONAL, string $default = ''): void
    {
        $this->definitions[] = new InputOption($name, $shortcut, $type, $default);
    }
}
