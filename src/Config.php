<?php

namespace Dive\Stateful;

use Illuminate\Support\Arr;

class Config
{
    private TransitionKeyGenerator $key;

    private array $transitions = [];

    public function __construct()
    {
        $this->key = TransitionKeyGenerator::make();
    }

    public static function make(): self
    {
        return new self();
    }

    public function allowTransition(Transition|string $from, ?string $to = null): self
    {
        if (is_string($from)) {
            $from = Transition::make($from, $to);
        }

        Arr::set($this->transitions, $this->key->generate($from->getFrom(), $from->getTo()), $from);

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
        return array_key_exists($this->key->generate($from, $to), $this->transitions);
    }
}
