<?php

namespace Tests\Fakes\States;

use Dive\Stateful\Contracts\Stateful;
use Dive\Stateful\InteractsWithState;

class StatefulObjectA implements Stateful
{
    use InteractsWithState;

    public function __construct()
    {
        $this->state = FromA::make($this);
    }
}
