<?php

namespace Hilmy\FeatureControl;

class Feature
{
    private array $registered;

    public function register(string $name, Condition $condition): bool
    {
        if (empty($name)) {
            return false;
        }
        $this->registered[$name] = $condition;
        return true;
    }

    public function remove(string $name): bool
    {
        if (empty($name)) {
            return false;
        }
        unset($this->registered[$name]);
        return true;
    }
}
