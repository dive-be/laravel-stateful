<?php declare(strict_types=1);

namespace Tests\Fakes\States;

use Dive\Stateful\Concerns\InteractsWithState;
use Dive\Stateful\Contracts\Stateful;

class StatefulObjectA implements Stateful
{
    use InteractsWithState;

    public function __construct()
    {
        $this->state = FromA::make($this);
    }
}
