<?php

namespace Hilmy\FeatureControl\Conditions;

class Condition
{
    private bool $enable = false;
    private Toggle $toggle;
    private Percentage $percentage;
    private Time $time;
    private Whitelist $whitelist;

    public function __construct(
        bool $toggle = false,
        int $percentage = 0,
        int $start = 0,
        int $end = 0,
        array $whitelist = []
    ) {
        $this->enable = true;
        $this->toggle = new Toggle($toggle);
        $this->percentage = new Percentage($percentage);
        $this->time = new Time($start, $end);
        $this->whitelist = new Whitelist($whitelist);
    }

    public function toString(): string
    {
        $enable = $this->enable ? '1' : '0';
        return $enable . ','
            . $this->toggle->toString() . ','
            . $this->percentage->toString() . ','
            . $this->time->toString() . ','
            . $this->whitelist->toString();
    }

    public function fromString(string $value): void
    {
        $exploded = explode(',', $value);
        $this->enable = @$exploded[0] == '1';
        $this->toggle->fromString(@$exploded[1] ?? '');
        $this->percentage->fromString(@$exploded[2] ?? '');
        $this->time->fromString(@$exploded[3] ?? '');
        $this->whitelist->fromString(@$exploded[4] ?? '');
    }

    public function enable(bool $enable = true): void
    {
        $this->enable = $enable;
    }

    public function toggle(bool $toggle = true): void
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

    public function check(mixed $id = null, int $now = 0): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }
        if ($this->isToggleOn()) {
            return true;
        }
        if ($this->isInPercentage(intval($id))) {
            return true;
        }
        if ($this->isInTimeRange($now)) {
            return true;
        }
        return $this->isWhitelisted($id);
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
