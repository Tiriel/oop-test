<?php

namespace App\Console\Core\Input;

enum InputOptionTypes
{
    case VALUE_REQUIRED;
    case VALUE_OPTIONAL;
    case NO_VALUE;
}
