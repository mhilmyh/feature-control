<?php

namespace Hilmy\FeatureControl\Conditions;

class Toggle
{
    private bool $switch = false;

    public function __construct(bool $switch)
    {
        $this->switch = $switch;
    }

    public function set(bool $switch): void
    {
        $this->switch = $switch;
    }

    public function check(): bool
    {
        return $this->switch;
    }

    public function switch()
    {
        $this->switch = !$this->switch;
    }

    public function toString(): string
    {
        return $this->switch ? '1' : '0';
    }

    public function fromString(string $value): void
    {
        $this->switch = $value == '1';
    }
}
