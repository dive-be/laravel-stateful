<?php declare(strict_types=1);

namespace Dive\Stateful\Contracts;

use Dive\Stateful\State;

interface Stateful
{
    public function getState(): State;

    public function setState(State $state): self;
}
