<?php

namespace Dive\Stateful;

use Dive\Stateful\Support\Makeable;
use Illuminate\Support\Arr;

final class Config
{
    use Makeable;

    private TransitionKeyGenerator $key;

    private array $transitions = [];

    public function __construct()
    {
        $this->key = new TransitionKeyGenerator();
    }

    public function allowTransition(string $from, ?string $to = null): self
    {
        $transition = is_string($to) ? DefaultTransition::make($from, $to) : call_user_func([$from, 'make']);

        Arr::set($this->transitions, $this->key->generate($transition->from(), $transition->to()), $transition);

        return $this;
    }

    public function getTransition(string $from, string $to): ?Transition
    {
        return Arr::get($this->transitions, $this->key->generate($from, $to));
    }

    public function getTransitions(): array
    {
        return $this->transitions;
    }

    public function isTransitionAllowed(string $from, string $to): bool
    {
        return $from === $to || array_key_exists($this->key->generate($from, $to), $this->transitions);
    }
}
