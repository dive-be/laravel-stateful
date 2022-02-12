<?php declare(strict_types=1);

namespace Dive\Stateful;

use Dive\Stateful\Support\Makeable;

final class TransitionKeyGenerator
{
    use Makeable;

    private const SEPARATOR = '=>';

    public function generate(string $from, string $to): string
    {
        return call_user_func([$from, 'name']) . self::SEPARATOR . call_user_func([$to, 'name']);
    }
}
