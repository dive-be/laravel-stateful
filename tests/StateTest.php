<?php

namespace Tests;

use Dive\Stateful\Config;
use Dive\Stateful\Exceptions\TransitionFailedException;
use Tests\Fakes\GuardedStates\StatefulObjectA;
use Tests\Fakes\GuardedStates\StatefulObjectB;
use Tests\Fakes\GuardedStates\ToA;
use Tests\Fakes\GuardedStates\ToB;
use Tests\Fakes\States\Another;
use Tests\Fakes\States\ExampleStateDeliberatelyMadeWithALongName;
use Tests\Fakes\States\From;
use Tests\Fakes\States\StatefulObject;
use Tests\Fakes\States\To;

it("can return the state's name in lower snake case", function () {
    expect(ExampleStateDeliberatelyMadeWithALongName::name())->toBe('example_state_deliberately_made_with_a_long_name');
});

it('can determine whether a transition is allowed to take place', function () {
    $state = new From(new StatefulObject());

    expect($state->canTransitionTo(Another::class))->toBeFalse();
    expect($state->canTransitionTo(To::class))->toBeTrue();
});

it('can transition a stateful object to its next state', function () {
    $object = new StatefulObject();

    expect($object->getState())->toBeInstanceOf(From::class);

    $object->getState()->transitionTo(To::class);

    expect($object->getState())->toBeInstanceOf(To::class);
});

it('throws if an illegal transition is attempted', function () {
    $object = new StatefulObject();

    $object->getState()->transitionTo(Another::class);
})->throws(TransitionFailedException::class);

it('throws if a guard disallows a valid transition', function () {
    $object = new StatefulObjectB();

    $object->getState()->transitionTo(ToB::class);
})->throws(TransitionFailedException::class);

it('does also accept a simple closure as the guard', function () {
    $object = new StatefulObjectA();

    $object->getState()->transitionTo(ToA::class);
})->throws(TransitionFailedException::class);
