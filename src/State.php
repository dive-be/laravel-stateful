<?php

namespace Dive\Stateful;

use Dive\Stateful\Contracts\Stateful;
use Dive\Stateful\Exceptions\TransitionFailedException;
use Illuminate\Support\Str;

abstract class State
{
    protected Config $config;

    public function __construct(protected Stateful $object)
    {
        $this->config = static::config();
    }

    public static function config(): Config
    {
        return Config::make();
    }

    public static function make(Stateful $object): static
    {
        return new static($object);
    }

    public static function name(): string
    {
        return Str::lower(Str::snake(class_basename(static::class)));
    }

    public function canTransitionTo(string $state): bool
    {
        return $this->config->isTransitionAllowed(static::class, $state);
    }

    public function transitionTo(string $state): Stateful
    {
        if (! $this->canTransitionTo($state)) {
            throw TransitionFailedException::make(static::class, $state);
        }

        return (new Transition($state, $this->object))->handle();
    }
}
