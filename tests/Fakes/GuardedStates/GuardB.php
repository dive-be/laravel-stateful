<?php

namespace Tests\Fakes\GuardedStates;

class GuardB
{
    public function __invoke(StatefulObjectB $object)
    {
        return false;
    }
}
