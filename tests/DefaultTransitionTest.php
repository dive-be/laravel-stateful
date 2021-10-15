<?php declare(strict_types=1);

namespace Tests;

use Dive\Stateful\DefaultTransition;
use Dive\Stateful\Exceptions\InvalidStateArgumentException;
use Tests\Fakes\States\FromA;
use Tests\Fakes\States\ToA;

it('throws if states are invalid', function ($from, $to) {
    DefaultTransition::make($from, $to);
})->throws(InvalidStateArgumentException::class)->with([
    ['stateA', 'stateB'],
    [FromA::class, 'stateC'],
    ['stateD', ToA::class],
]);
