<?php

namespace Tests\Fakes;

use Dive\Stateful\Config;
use Dive\Stateful\State;

abstract class CommonState extends State
{
    public static function config(): Config
    {
        return parent::config()->allowTransition(From::class, To::class);
    }
}
