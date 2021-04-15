<?php

namespace Tests;

use Dive\Stateful\Transition;
use Mockery;
use Tests\Fakes\States\StatefulObject;
use Tests\Fakes\States\To;

it("sets the object's new state", function () {
    $object = Mockery::mock(StatefulObject::class);
    $object
        ->shouldReceive('setState')
        ->once()
        ->withArgs(fn ($newState) => $newState instanceof To);

    (new Transition(To::class, $object))->handle();
});
