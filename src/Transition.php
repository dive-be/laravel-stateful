<?php

namespace Dive\Stateful;

use Illuminate\Contracts\Support\Arrayable;

class Transition implements Arrayable
{
    private $after = null;

    private $before = null;

    private $guard = null;

    private function __construct(
        private ?string $from = null,
        private ?string $to = null,
    ) {}

    public static function make(?string $from = null, ?string $to = null)
    {
        return new self($from, $to);
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

    public function from(string $from): self
    {
        $this->from = $from;

        return $this;
    }

    public function guard(callable|string $callback): self
    {
        $this->guard = $callback;

        return $this;
    }

    public function to(string $to): self
    {
        $this->to = $to;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'after' => $this->after,
            'before' => $this->before,
            'from' => $this->from,
            'guard' => $this->guard,
            'to' => $this->to,
        ];
    }
}
