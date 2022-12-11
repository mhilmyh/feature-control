<?php

namespace Hilmy\FeatureControl\Conditions;

class Percentage
{
    private int $modulo = 100;
    private int $percent = 0;

    public function __construct(int $percent = 0)
    {
        $this->percent = $percent;
    }

    public function set(int $percent = 0): void
    {
        $this->percent = $percent;
    }

    public function check(int $value = -1): bool
    {
        if ($value < 0) {
            return false;
        }
        $remainder = $value % $this->modulo;
        return $remainder <= $this->percent;
    }

    public function toString()
    {
        return strval($this->percent);
    }
}
