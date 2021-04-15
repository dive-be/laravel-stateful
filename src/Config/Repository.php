<?php

namespace Dive\Stateful\Config;

use Dive\Stateful\Exceptions\InvalidConfigurationException;
use Dive\Stateful\TransitionFingerprintGenerator;
use Illuminate\Support\Arr;

class Repository
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

    public function allowTransition(Transition|string $from, ?string $to = null): self
    {
        $transition = ['after' => null, 'before' => null, 'guard' => null];

        if ($from instanceof Transition) {
            $transition = $from->toArray();
            $from = Arr::pull($transition, 'from');
            $to = Arr::pull($transition, 'to');
        }

        $fingerprint = $this->generator->from($from)->to($to)->generate();

        Arr::set($this->transitions, $fingerprint, $transition);

        return $this;
    }

    public function getAfterCallback(string $from, string $to): ?callable
    {
        return $this->resolve($this->getProperty($from, $to, 'after'));
    }

    public function getBeforeCallback(string $from, string $to): ?callable
    {
        return $this->resolve($this->getProperty($from, $to, 'before'));
    }

    public function getGuard(string $from, string $to): callable
    {
        if (! $this->isGuarded($from, $to)) {
            throw InvalidConfigurationException::unguarded($from, $to);
        }

        return $this->resolve($this->getProperty($from, $to, 'guard'));
    }

    public function getTransitions(): array
    {
        return $this->transitions;
    }

    public function isGuarded(string $from, string $to): bool
    {
        return ! is_null($this->getProperty($from, $to, 'guard'));
    }

    public function isTransitionAllowed(string $from, string $to): bool
    {
        return is_array($this->getProperty($from, $to));
    }

    private function getProperty(string $from, string $to, ?string $property = null)
    {
        $fingerprint = $this->generator->from($from)->to($to)->generate();

        return Arr::get($this->transitions, $fingerprint.(is_string($property) ? '.'.$property : ''));
    }

    private function resolve($value)
    {
        return is_string($value) && class_exists($value) ? app($value) : $value;
    }
}
