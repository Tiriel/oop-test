<?php

namespace App\Console;

use App\Console\Core\ArgvInput;
use App\Container;

class Application
{
    protected Container $container;
    protected ArgvInput $input;

    public function __construct()
    {
        $this->container = Container::create();
        $this->input = new ArgvInput();
    }

    public function run(): int
    {
        var_dump($this->input->getArguments());
        var_dump($this->input->getOptions());
        return 0;
    }
}
