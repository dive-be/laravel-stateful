<?php

namespace Tests\Fakes\GuardedStates;

use Dive\Stateful\Config\Repository;
use Dive\Stateful\State;

abstract class CommonState extends State
{
    public static function config(): Repository
    {
        return parent::config()
            ->allowTransition(FromA::class, ToA::class, function () {
                return false;
            })
            ->allowTransition(FromB::class, ToB::class, GuardB::class);
    }
}
