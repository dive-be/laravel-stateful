<?php

namespace Dive\Stateful\Exceptions;

use Exception;

class InvalidStateArgumentException extends Exception
{
    public static function doesNotExist(string $value): self
    {
        return new self("`{$value}` must be a valid state class.");
    }

    public static function doesNotExtendState(string $value, string $class): self
    {
        return new self("`{$value}` must extend `{$class}`.");
    }
}
