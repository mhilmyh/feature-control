<?php

namespace Hilmy\FeatureControl\Conditions;

class Whitelist
{
    private array $listed;

    public function __construct(array $listed)
    {
        $this->listed = $listed;
    }

    public function set(array $listed): void
    {
        $this->listed = $listed;
    }

    public function check(mixed $item = null): bool
    {
        if (empty($item)) {
            return false;
        }
        return array_search($item, $this->listed);
    }

    public function toString(): string
    {
        return implode(':', $this->listed);
    }

    public function fromString(string $value): void
    {
        $this->listed = explode(':', $value);
    }
}
