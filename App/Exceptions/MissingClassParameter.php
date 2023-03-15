<?php

namespace App\Exceptions;

use Core\Abstractions\VortexException;
use Monolog\Level;

class MissingClassParameter extends VortexException
{
    public function __construct()
    {
        parent::__construct('Missing Dummy class', 500, Level::Error->value);
    }
}
