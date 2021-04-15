<?php

namespace Dive\Stateful;

class TransitionKeyGenerator
{
    public const SEPARATOR = '=>';

    public static function make(): self
    {
        return new self();
    }

    public function generate(string $from, string $to): string
    {
        return call_user_func([$from, 'name']).self::SEPARATOR.call_user_func([$to, 'name']);
    }
}
