<?php

namespace App\Exceptions;

use Core\Abstractions\VortexException;
use Monolog\Level;

class RelationNotFound extends VortexException
{
    public function __construct(string $relation_name)
    {
        parent::__construct('Try create class with "' . $relation_name . '" relation',
            500,
            Level::Error->value
        );
    }
}
