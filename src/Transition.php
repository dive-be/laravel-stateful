<?php

namespace Dive\Stateful;

use Closure;
use Dive\Stateful\Contracts\Stateful;
use Dive\Stateful\Support\Makeable;

abstract class Transition
{
    use Makeable;

    abstract public function from(): string;

    abstract public function to(): string;

    public function runAfterHook(Stateful $object, string $state): void
    {
        if (method_exists($this, 'after')) {
            $this->after($state, $object);
        }
    }

    public function runBeforeHook(Stateful $object, string $state): void
    {
        if (method_exists($this, 'before')) {
            $this->before($state, $object);
        }
    }

    public function whenGuarded(Stateful $object, Closure $callback): void
    {
        if (! method_exists($this, 'guard')) {
            return;
        }

        if ($this->guard($object)) {
            return;
        }

        $callback();
    }
}
