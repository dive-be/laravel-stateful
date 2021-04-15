<?php

namespace Tests\Fakes;

use Dive\Stateful\Contracts\Stateful;
use Dive\Stateful\InteractsWithState;

class StatefulObject implements Stateful
{
    use InteractsWithState;

    public function __construct()
    {
        $this->state = From::make($this);
    }
}
