<?php

namespace Tests;

use Tests\Fakes\States\From;
use Tests\Fakes\States\StatefulObject;
use Tests\Fakes\States\To;

it('provides a getter/setter for a local state property', function () {
    $object = new StatefulObject();

    expect($object->getState())->toBeInstanceOf(From::class);

    $object->setState(To::make($object));

    expect($object->getState())->toBeInstanceOf(To::class);
});
