<?php declare(strict_types=1);

namespace Dive\Stateful;

use Dive\Stateful\Contracts\Stateful;
use Dive\Stateful\Exceptions\TransitionFailedException;
use Dive\Stateful\Support\Makeable;
use Illuminate\Support\Str;

abstract class State
{
    use Makeable;

    protected Config $config;

    final public function __construct(protected Stateful $object)
    {
        $this->config = static::config();
    }

    public static function config(): Config
    {
        return Config::make();
    }

    public static function from(string $name): string
    {
        return Str::of(static::class)
            ->beforeLast('\\')
            ->append('\\', Str::studly($name))
            ->__toString();
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

        if (! $transition->runGuard($this->object)) {
            throw TransitionFailedException::guarded($from, $to);
        }

        $transition->runBeforeHook($this->object);

        $object = $this->object->setState(new $to($this->object));

        $transition->runAfterHook($this->object);

        return $object;
    }
}
