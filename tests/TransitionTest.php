<?php

namespace Tests;

use Dive\Stateful\Exceptions\InvalidStateArgumentException;
use Dive\Stateful\Transition;
use Tests\Fakes\States\From;
use Tests\Fakes\States\To;

it('can fluently set properties', function () {
    $trans = Transition::make(From::class, To::class);

    expect($trans->after(fn () => null))->toEqual($trans);
    expect($trans->before(fn () => null))->toEqual($trans);
    expect($trans->guard(fn () => null))->toEqual($trans);
});

it('throws if states are invalid', function ($from, $to) {
    Transition::make($from, $to);
})->throws(InvalidStateArgumentException::class)->with([
    ['stateA', 'stateB'],
    [From::class, 'stateC'],
    ['stateD', To::class],
]);
