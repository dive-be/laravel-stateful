<?php

namespace Tests;

use Dive\Stateful\Exceptions\TransitionFailedException;
use Tests\Fakes\CustomTransitions\StatefulObjectB;
use Tests\Fakes\CustomTransitions\ToB;
use Tests\Fakes\States\Another;
use Tests\Fakes\States\ExampleStateDeliberatelyMadeWithALongName;
use Tests\Fakes\States\FromA;
use Tests\Fakes\States\StatefulObjectA;
use Tests\Fakes\States\ToA;

it("can return the state's name in camel case", function () {
    expect(ExampleStateDeliberatelyMadeWithALongName::name())->toBe('exampleStateDeliberatelyMadeWithALongName');
});

it('can determine whether a transition is allowed to take place', function () {
    $state = new FromA(new StatefulObjectB());

    expect($state->canTransitionTo(Another::class))->toBeFalse();
    expect($state->canTransitionTo(ToA::class))->toBeTrue();
});

it('can transition a stateful object to its next state', function () {
    $object = new StatefulObjectA();

    expect($object->getState())->toBeInstanceOf(FromA::class);

    $object->getState()->transitionTo(ToA::class);

    expect($object->getState())->toBeInstanceOf(ToA::class);
});

it('throws if an illegal transition is attempted', function () {
    $object = new StatefulObjectB();

    $object->getState()->transitionTo(Another::class);
})->throws(TransitionFailedException::class);

it('throws if a guard disallows a valid transition', function () {
    $object = new StatefulObjectB();

    $object->getState()->transitionTo(ToB::class);
})->throws(TransitionFailedException::class);
