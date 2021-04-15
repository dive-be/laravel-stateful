<?php

namespace Tests\Fakes\States;

use Dive\Stateful\Config\Repository;
use Dive\Stateful\State;

abstract class CommonState extends State
{
    public static function config(): Repository
    {
        return parent::config()->allowTransition(From::class, To::class);
    }
}
