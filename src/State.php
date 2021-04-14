<?php

namespace Dive\Stateful;

use Dive\Stateful\Contracts\Stateful;
use Dive\Stateful\Exceptions\TransitionFailedException;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

abstract class State
{
    protected array $transitions = [];

    public function __construct(protected Stateful $object) {}

    public static function make(Stateful $object): static
    {
        return new static($object);
    }

    public function canTransitionTo(string $state): bool
    {
        $transitions = Arr::get($this->transitions, static::class);

        if (is_null($transitions)) {
            return false;
        }

        if (is_string($transitions)) {
            return $transitions === $state;
        }

        return in_array($state, $transitions);
    }

    public function name(): string
    {
        return Str::lower(class_basename(static::class));
    }

    public function transitionTo(string $state): Stateful
    {
        if (! $this->canTransitionTo($state)) {
            throw TransitionFailedException::make(static::class, $state);
        }

        return (new Transition($state, $this->object))->handle();
    }
}
