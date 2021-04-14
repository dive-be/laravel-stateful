<?php

namespace Dive\Stateful\Exceptions;

use Exception;

class TransitionFailedException extends Exception
{
    public static function make(string $from, string $to): self
    {
        return new self("The transition from `{$from}` to `{$to}` is not allowed.");
    }
}
