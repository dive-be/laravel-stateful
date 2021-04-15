<?php

namespace Tests;

use Dive\Stateful\Config;
use Dive\Stateful\Exceptions\FingerprintException;
use Dive\Stateful\TransitionFingerprintGenerator;
use Tests\Fakes\From;
use Tests\Fakes\To;

beforeEach(function () {
    $this->fingerprint = TransitionFingerprintGenerator::make();
});

it("can generate a transition's fingerprint/key/identifier", function () {
    $fingerprint = $this->fingerprint->from(From::class)->to(To::class)->generate();

    expect($fingerprint)->toBe('from:to');
});

it('throws if a fingerprint is being generated without setting any values first', function () {
    $this->fingerprint->generate();
})->throws(FingerprintException::class);

it('throws if a fingerprint is being generated without a complete transition or invalid states', function ($from, $to) {
    $this->fingerprint->from($from)->to($to)->generate();
})->throws(FingerprintException::class)->with([
    ['', ''],
    [From::class, ''],
    ['', To::class],
    [Config::class, To::class],
    [From::class, Config::class],
]);
