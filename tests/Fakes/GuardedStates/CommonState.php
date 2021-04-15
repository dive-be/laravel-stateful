<?php

namespace Tests\Fakes\GuardedStates;

use Dive\Stateful\Config;
use Dive\Stateful\State;
use Dive\Stateful\Transition;

abstract class CommonState extends State
{
    public static function config(): Config
    {
        return parent::config()
            ->allowTransition(Transition::make(FromA::class, ToA::class)->guard(fn () => false))
            ->allowTransition(Transition::make(FromB::class, ToB::class)->guard(GuardB::class));
    }
}
