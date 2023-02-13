<?php

namespace App\Console;

use App\Console\Command\Command;
use App\Console\Core\Input\ArgvInput;
use App\Console\Core\Input\InputArgument;
use App\Console\Core\Input\InputOption;
use App\Console\Core\Output\ConsoleStreamOutput;
use App\Container;

class Application
{
    protected Container $container;
    protected ArgvInput $input;
    protected ConsoleStreamOutput $output;
    protected ?string $commandName;
    protected Command $command;

    public function __construct()
    {
        $this->container = Container::create();
        $this->input = new ArgvInput();
        $this->output = new ConsoleStreamOutput();

        $this->commandName = $this->input->getCommandName();
        $this->command = $this->container->getCommand($this->commandName, $this->input, $this->output);

        $this->input->loadDefinitions($this->command->getDefinitions());
        $this->input->parse();
    }

    public function run(): int
    {
        $this->output->writeln('Command name :');
        $this->output->writeln(sprintf(" - %s", $this->commandName));

        $this->output->writeln('Arguments :');
        foreach ($this->input->getArguments() as $key => $argument) {
            $this->output->writeln(sprintf(" - %s : %s", $key, $argument));
        }

        $this->output->writeln('Options :');
        foreach ($this->input->getOptions() as $index => $option) {
            $this->output->writeln(sprintf(" - %s : %s", $index, $option));
        }

        $code = $this->command->execute($this->input, $this->output);

        return 0;
    }
}
