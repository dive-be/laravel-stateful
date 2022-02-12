<?php declare(strict_types=1);

namespace Tests\Fakes\CustomTransitions;

use Dive\Stateful\Concerns\InteractsWithState;
use Dive\Stateful\Contracts\Stateful;

class StatefulObjectB implements Stateful
{
    use InteractsWithState;

    public function __construct()
    {
        $this->state = FromB::make($this);
    }
}
