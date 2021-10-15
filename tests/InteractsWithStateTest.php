<?php declare(strict_types=1);

namespace Tests;

use Tests\Fakes\States\FromA;
use Tests\Fakes\States\StatefulObjectA;
use Tests\Fakes\States\ToA;

it('provides a getter/setter for a local state property', function () {
    $object = new StatefulObjectA();

    expect($object->getState())->toBeInstanceOf(FromA::class);

    $object->setState(ToA::make($object));

    expect($object->getState())->toBeInstanceOf(ToA::class);
});
