<?php

namespace Dive\Stateful;

use Dive\Stateful\Exceptions\FingerprintException;

class TransitionFingerprintGenerator
{
    public const SEPARATOR = ':';

    private string $from = '';

    private string $to = '';

    public static function make(): self
    {
        return new self();
    }

    public function from(string $value): self
    {
        $this->verifyValue($value);

        $this->from = $value;

        return $this;
    }

    public function generate(): string
    {
        if (empty($this->from) || empty($this->to)) {
            throw FingerprintException::missingTransition();
        }

        $fingerprint = call_user_func([$this->from, 'name']).self::SEPARATOR.call_user_func([$this->to, 'name']);

        $this->from = '';
        $this->to = '';

        return $fingerprint;
    }

    public function to(string $value): self
    {
        $this->verifyValue($value);

        $this->to = $value;

        return $this;
    }

    private function verifyValue(string $value)
    {
        if (! class_exists($value)) {
            throw FingerprintException::doesNotExist($value);
        }

        if (! is_subclass_of($value, $class = State::class)) {
            throw FingerprintException::doesNotExtendState($value, $class);
        }
    }
}
