<?php

namespace Dive\Stateful\Exceptions;

use Exception;

class InvalidConfigurationException extends Exception
{
    public static function unguarded(string $from, $to): self
    {
        return new self("The transition from `{$from}` to `{$to}` is not guarded.");
    }
}
