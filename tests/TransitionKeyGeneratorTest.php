<?php

namespace Tests;

use Dive\Stateful\TransitionKeyGenerator;
use Tests\Fakes\States\From;
use Tests\Fakes\States\To;

it("can generate a transition's key", function () {
    $key = TransitionKeyGenerator::make()->generate(From::class, To::class);

    expect($key)->toBe('from=>to');
});
