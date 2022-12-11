<?php

namespace Hilmy\FeatureControl\Conditions;

class Time
{
    private int $start = 0;
    private int $end = 0;

    public function __construct(int $start = 0, int $end = 0)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function set(int $start = 0, int $end = 0): void
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function check(int $now = -1): bool
    {
        return $this->start <= $now && $now < $this->end;
    }

    public function toString(): string
    {
        return strval($this->start) . '~' . strval($this->end);
    }

    public function fromString(string $value): void
    {
        $time = explode('~', $value);
        $this->start = intval(@$time[0] ?? 0);
        $this->end = intval(@$time[1] ?? 0);
    }
}
