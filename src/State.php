<?php

namespace Dive\Stateful;

use Dive\Stateful\Contracts\Stateful;
use Dive\Stateful\Exceptions\TransitionFailedException;
use Illuminate\Support\Str;

abstract class State
{
    protected Config $config;

    final public function __construct(protected Stateful $object)
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
        return Str::camel(class_basename(static::class));
    }

    public function canTransitionTo(string $state): bool
    {
        return $this->config->isTransitionAllowed(static::class, $state);
    }

    public function transitionTo(string $to): Stateful
    {
        if (($from = static::class) === $to) {
            return $this->object;
        }

        if (! $this->canTransitionTo($to)) {
            throw TransitionFailedException::disallowed($from, $to);
        }

        $transition = $this->config->getTransition($from, $to);

        if ($transition->isGuarded() && ! call_user_func($transition->getGuard(), $this->object)) {
            throw TransitionFailedException::guarded($from, $to);
        }

        if ($before = $transition->getBefore()) {
            $before($to, $this->object);
        }

        $object = $this->object->setState(new $to($this->object));

        if ($after = $transition->getAfter()) {
            $after($from, $this->object);
        }

        return $object;
    }
}
