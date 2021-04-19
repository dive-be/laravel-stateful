<?php

namespace Dive\Stateful;

use Dive\Stateful\Contracts\Stateful;
use Dive\Stateful\Support\Makeable;

abstract class Transition
{
    use Makeable;

    abstract public function from(): string;

    abstract public function to(): string;

    public function runAfterHook(Stateful $object)
    {
        if (method_exists($this, 'after')) {
            app()->call([$this, 'after'], compact('object'));
        }
    }

    public function runBeforeHook(Stateful $object)
    {
        if (method_exists($this, 'before')) {
            app()->call([$this, 'before'], compact('object'));
        }
    }

    public function runGuard(Stateful $object)
    {
        return method_exists($this, 'guard')
            ? app()->call([$this, 'guard'], compact('object'))
            : true;
    }
}
