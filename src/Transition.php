<?php declare(strict_types=1);

namespace Dive\Stateful;

use Dive\Stateful\Contracts\Stateful;
use Dive\Stateful\Support\Makeable;
use ReflectionMethod;
use ReflectionUnionType;

abstract class Transition
{
    use Makeable;

    abstract public function from(): string;

    abstract public function to(): string;

    public function runAfterHook(Stateful $object): void
    {
        if (method_exists($this, $method = 'after')) {
            $this->runMethod($method, $object);
        }
    }

    public function runBeforeHook(Stateful $object): void
    {
        if (method_exists($this, $method = 'before')) {
            $this->runMethod($method, $object);
        }
    }

    public function runGuard(Stateful $object): bool
    {
        return method_exists($this, $method = 'guard')
            ? $this->runMethod($method, $object) : true;
    }

    private function runMethod(string $name, Stateful $object): mixed
    {
        $parameters = (new ReflectionMethod($this, $name))->getParameters();

        foreach ($parameters as $idx => $parameter) {
            if ($parameter->isVariadic()) {
                continue;
            }

            $type = $parameter->getType();

            if ($type instanceof ReflectionUnionType) {
                $type = null;
            } else {
                $type = $type->getName();
            }

            if (is_null($type)) {
                $parameters[$idx] = $type;
            } elseif ($type === Stateful::class || is_subclass_of($type, Stateful::class)) {
                $parameters[$idx] = $object;
            } else {
                $parameters[$idx] = app($type);
            }
        }

        return call_user_func_array([$this, $name], $parameters);
    }
}
