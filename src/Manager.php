<?php

namespace Hilmy\FeatureControl;

use Hilmy\FeatureControl\Conditions\Condition;
use Hilmy\FeatureControl\Storages\Storage;

class Manager
{
    private array $cached;
    private Storage $storage;

    public function __construct(string $basePath = '')
    {
        $this->cached = [];
        $this->storage = new Storage($basePath);
    }

    public function cache(string $name = ''): Condition|array|null
    {
        return $name != '' ? @$this->cached[$name] : $this->cached;
    }

    public function storage(): Storage
    {
        return $this->storage;
    }

    public function check(string $name, mixed $id): bool
    {
        if ($name == '' || empty($id)) {
            return false;
        }
        $condition = $this->get($name);
        if (is_null($condition) || is_array($condition)) {
            return false;
        }
        return $condition->check($id, time());
    }

    public function set(
        string $name,
        Condition|array|null $condition = null,
        bool $toggle = false,
        int $percentage = 0,
        int $start = 0,
        int $end = 0,
        array $whitelist = [],
        bool $store = false,
    ): bool {
        if ($name == '') {
            return false;
        }
        $value = null;
        if (is_array($condition)) {
            $value = new Condition(
                @$condition['toggle'] ?? $toggle,
                @$condition['percentage'] ?? $percentage,
                @$condition['start'] ?? $start,
                @$condition['end'] ?? $end,
                @$condition['whitelist'] ?? $whitelist,
            );
        } else if (is_null($condition)) {
            $value = new Condition(
                $toggle,
                $percentage,
                $start,
                $end,
                $whitelist
            );
        }
        if ($store) {
            $this->storage->set($name, $value);
        }
        $this->cached[$name] = $value;
        return true;
    }

    public function backup(): void
    {
        foreach ($this->cached as $name => $value) {
            if (!$value instanceof Condition || $name == '') {
                continue;
            }
            $this->storage->set($name, $value);
        }
    }

    public function load(): array
    {
        $this->cached = $this->storage->all($this->storage->base());
        return $this->cached;
    }

    public function get(string $name = '', bool $stored = false): Condition|null
    {
        if ($name == '') {
            return null;
        }
        if (!$stored) {
            return @$this->cached[$name];
        }
        $value = $this->storage->get($name);
        $this->cached[$name] = $value;
        return $value;
    }

    public function delete(string $name = '', bool $stored = false): bool
    {
        if ($name == '') {
            return false;
        }
        unset($this->cached[$name]);
        if ($stored) {
            return $this->storage->delete($name);
        }
        return true;
    }
}
