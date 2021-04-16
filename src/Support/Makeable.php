<?php

namespace Dive\Stateful\Support;

trait Makeable
{
    public static function make(...$arguments)
    {
        return new static(...$arguments);
    }
}
