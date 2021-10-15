<?php declare(strict_types=1);

namespace Tests\Fakes\States;

use Dive\Stateful\Config;
use Dive\Stateful\State;

abstract class CommonState extends State
{
    public static function config(): Config
    {
        return parent::config()->allowTransition(FromA::class, ToA::class);
    }
}
