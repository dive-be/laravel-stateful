<?php

namespace Tests;

use Dive\Stateful\Config;
use Dive\Stateful\Exceptions\TransitionFailedException;
use Tests\Fakes\Another;
use Tests\Fakes\ExampleStateDeliberatelyMadeWithALongName;
use Tests\Fakes\From;
use Tests\Fakes\StatefulObject;
use Tests\Fakes\To;

it("can return the state's name in lower snake case", function () {
    expect(ExampleStateDeliberatelyMadeWithALongName::name())->toBe('example_state_deliberately_made_with_a_long_name');
});

it('can return a default, empty transition configuration', function () {
    expect($config = ExampleStateDeliberatelyMadeWithALongName::config())->toBeInstanceOf(Config::class);
    expect($config->getTransitions())->toBeEmpty();
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
