<?php declare(strict_types=1);

namespace Tests\Fakes\CustomTransitions;

use Dive\Stateful\Transition;

class CustomTransition extends Transition
{
    public function from(): string
    {
        return FromB::class;
    }

    public function guard(StatefulObjectB $object)
    {
        return false;
    }

    public function to(): string
    {
        return ToB::class;
    }
}
