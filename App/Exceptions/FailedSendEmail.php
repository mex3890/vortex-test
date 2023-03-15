<?php

namespace App\Exceptions;

use Core\Abstractions\VortexException;
use Core\Helpers\DateTime;
use Monolog\Level;

class FailedSendEmail extends VortexException
{
    public function __construct(string $email)
    {
        parent::__construct("Failed on send email to $email at " . DateTime::currentDateTime(), 500, Level::Critical->value);
    }
}
