<?php

namespace App\Console\Core\Input;

enum InputArgumentTypes
{
    case REQUIRED;
    case OPTIONAL;
    case IS_ARRAY;
}
