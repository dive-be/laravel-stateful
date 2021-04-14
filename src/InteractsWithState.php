<?php

namespace Dive\Stateful;

use Dive\Stateful\Contracts\Stateful;

trait InteractsWithState
{
    protected State $state;

    public function getState(): State
    {
        return $this->state;
    }

    public function setState(State $state): Stateful
    {
        $this->state = $state;

        return $this;
    }
}
