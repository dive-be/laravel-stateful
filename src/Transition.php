<?php

namespace Dive\Stateful;

use Dive\Stateful\Contracts\Stateful;

class Transition
{
    public function __construct(
        private string $to,
        private Stateful $object,
    ) {}

    public function handle(): Stateful
    {
        return $this->object->setState(new $this->to($this->object));
    }
}
