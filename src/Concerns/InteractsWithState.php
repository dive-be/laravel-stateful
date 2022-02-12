<?php declare(strict_types=1);

namespace Dive\Stateful\Concerns;

use Dive\Stateful\Contracts\Stateful;
use Dive\Stateful\State;

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

    public function canTransitionTo(string $state): bool
    {
        return $this->state->canTransitionTo($state);
    }

    /**
     * @throws \Dive\Stateful\Exceptions\TransitionFailedException
     */
    public function transitionTo(string $to): self
    {
        return $this->state->transitionTo($to);
    }
}
