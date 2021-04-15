<?php

namespace Dive\Stateful\Exceptions;

use Exception;

class FingerprintException extends Exception
{
    public static function doesNotExist(string $value): self
    {
        return new self("`{$value}` must be a valid state class.");
    }

    public static function doesNotExtendState(string $value, string $class): self
    {
        return new self("`{$value}` must extend `{$class}`.");
    }

    public static function missingTransition(): self
    {
        return new self('The `from` and `to` properties must be set before creating a fingerprint.');
    }
}
