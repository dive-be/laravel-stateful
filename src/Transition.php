<?php

namespace Dive\Stateful;

use Dive\Stateful\Exceptions\InvalidStateArgumentException;

class Transition
{
    private $after = null;

    private $before = null;

    private $guard = null;

    private function __construct(
        private string $from,
        private string $to,
    ) {
        $this->verifyState($from);
        $this->verifyState($to);
    }

    public static function make(string $from, string $to)
    {
        return new self($from, $to);
    }

    public function getAfter(): callable|string|null
    {
        return $this->resolveCallable($this->after);
    }

    public function getBefore(): callable|null
    {
        return $this->resolveCallable($this->before);
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function getGuard(): callable|null
    {
        return $this->resolveCallable($this->guard);
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function after(callable|string $callback): self
    {
        $this->after = $callback;

        return $this;
    }

    public function before(callable|string $callback): self
    {
        $this->before = $callback;

        return $this;
    }

    public function guard(callable|string $callback): self
    {
        $this->guard = $callback;

        return $this;
    }

    public function isGuarded()
    {
        return ! is_null($this->guard);
    }

    private function resolveCallable(callable|string|null $callback): callable|null
    {
        if (is_string($callback) && class_exists($callback)) {
            return app($callback);
        }

        return $callback;
    }

    private function verifyState(string $state)
    {
        if (! class_exists($state)) {
            throw InvalidStateArgumentException::doesNotExist($state);
        }

        if (! is_subclass_of($state, $class = State::class)) {
            throw InvalidStateArgumentException::doesNotExtendState($state, $class);
        }
    }
}
