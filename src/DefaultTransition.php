<?php declare(strict_types=1);

namespace Dive\Stateful;

use Dive\Stateful\Exceptions\InvalidStateArgumentException;

final class DefaultTransition extends Transition
{
    public function __construct(
        private string $from,
        private string $to,
    ) {
        $this->verifyState($from);
        $this->verifyState($to);
    }

    public function from(): string
    {
        return $this->from;
    }

    public function to(): string
    {
        return $this->to;
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
