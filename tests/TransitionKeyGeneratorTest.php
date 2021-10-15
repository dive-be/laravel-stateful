<?php declare(strict_types=1);

namespace Tests;

use Dive\Stateful\TransitionKeyGenerator;
use Tests\Fakes\States\FromA;
use Tests\Fakes\States\ToA;

it("can generate a transition's key", function () {
    $key = TransitionKeyGenerator::make()->generate(FromA::class, ToA::class);

    expect($key)->toBe('fromA=>toA');
});
