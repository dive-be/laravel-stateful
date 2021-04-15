<?php

namespace Dive\Stateful;

use Dive\Stateful\Exceptions\InvalidConfigurationException;
use Illuminate\Support\Arr;

class Config
{
    private TransitionFingerprintGenerator $generator;

    private array $transitions = [];

    public function __construct()
    {
        $this->generator = TransitionFingerprintGenerator::make();
    }

    public static function make(): self
    {
        return new self();
    }

    public function allowTransition(string $from, string $to, callable|string|null $guard = null): self
    {
        $fingerprint = $this->generator->from($from)->to($to)->generate();

        Arr::set($this->transitions, $fingerprint, $guard);

        return $this;
    }

    public function getGuard(string $from, string $to): callable
    {
        if (! $this->isGuarded($from, $to)) {
            throw InvalidConfigurationException::unguarded($from, $to);
        }

        $fingerprint = $this->generator->from($from)->to($to)->generate();

        $guard = Arr::get($this->transitions, $fingerprint);

        if (is_string($guard) && class_exists($guard)) {
            $guard = app($guard);
        }

        return $guard;
    }

    public function getTransitions(): array
    {
        return $this->transitions;
    }

    public function isGuarded(string $from, string $to): bool
    {
        $fingerprint = $this->generator->from($from)->to($to)->generate();

        return Arr::has($this->transitions, $fingerprint);
    }

    public function isTransitionAllowed(string $from, string $to): bool
    {
        $fingerprint = $this->generator->from($from)->to($to)->generate();

        return array_key_exists($fingerprint, $this->transitions);
    }
}
