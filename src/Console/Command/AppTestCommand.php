<?php

namespace App\Console\Command;

use App\Console\Core\Input\InputArgumentTypes;
use App\Console\Core\Input\InputInterface;
use App\Console\Core\Output\OutputInterface;

class AppTestCommand extends Command
{
    protected function initialize(): void
    {
        $this->addArgument('foo', InputArgumentTypes::REQUIRED, 'bar');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $foo = $input->getArgument('foo');
        $output->writeln(sprintf("Argument passed : %s", $foo));
        $output->writeln('Works!');

        return 0;
    }
}
