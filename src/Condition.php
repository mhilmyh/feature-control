<?php

namespace Hilmy\FeatureControl;

use Hilmy\FeatureControl\Conditions\Toggle;
use Hilmy\FeatureControl\Conditions\Percentage;
use Hilmy\FeatureControl\Conditions\Time;
use Hilmy\FeatureControl\Conditions\Whitelist;

class Condition
{
    private bool $enable = false;
    private Toggle $toggle;
    private Percentage $percentage;
    private Time $time;
    private Whitelist $whitelist;

    public function __construct(bool $toggle, int $percentage, int $start, int $end, array $whitelist)
    {
        $this->enable = true;
        $this->toggle = new Toggle($toggle);
        $this->percentage = new Percentage($percentage);
        $this->time = new Time($start, $end);
        $this->whitelist = new Whitelist($whitelist);
    }

    public function toString()
    {
    }

    public function enable(bool $enable = false): void
    {
        $this->enable = $enable;
    }

    public function toggle(bool $toggle = false): void
    {
        $this->toggle->set($toggle);
    }

    public function percentage(int $percentage = 0): void
    {
        $this->percentage->set($percentage);
    }

    public function time(int $start = 0, int $end = 0): void
    {
        $this->time->set($start, $end);
    }

    public function whitelist(array $whitelist = []): void
    {
        $this->whitelist->set($whitelist);
    }

    public function isEnabled(): bool
    {
        return $this->enable;
    }

    public function isToggleOn(): bool
    {
        return $this->toggle->check();
    }

    public function isInPercentage(int $value = -1): bool
    {
        return $this->percentage->check($value);
    }

    public function isInTimeRange(int $now = -1): bool
    {
        return $this->time->check($now);
    }

    public function isWhitelisted(mixed $item): bool
    {
        return $this->whitelist->check($item);
    }
}
