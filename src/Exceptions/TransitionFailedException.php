<?php declare(strict_types=1);

namespace Dive\Stateful\Exceptions;

use Exception;

class TransitionFailedException extends Exception
{
    public static function disallowed(string $from, string $to): self
    {
        return new self("The transition from `{$from}` to `{$to}` is not allowed.");
    }

    public static function guarded(string $from, string $to): self
    {
        return new self("The transition from `{$from}` to `{$to}` was blocked by a guard.");
    }
}
