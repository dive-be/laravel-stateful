<?php

namespace Tests\Fakes\GuardedStates;

use Dive\Stateful\Config\Repository;
use Dive\Stateful\Config\Transition;
use Dive\Stateful\State;

abstract class CommonState extends State
{
    public static function config(): Repository
    {
        return parent::config()
            ->allowTransition(Transition::make(FromA::class, ToA::class)->guard(fn () => false))
            ->allowTransition(Transition::make(FromB::class, ToB::class)->guard(GuardB::class));
    }
}
