<?php

namespace Tests\Fakes\CustomTransitions;

use Dive\Stateful\Contracts\Stateful;
use Dive\Stateful\InteractsWithState;

class StatefulObjectB implements Stateful
{
    use InteractsWithState;

    public function __construct()
    {
        $this->state = FromB::make($this);
    }
}
