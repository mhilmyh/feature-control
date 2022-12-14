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
        if ($name != '') {
            return $this->cached[$name];
        }
        return $this->cached;
    }

    public function storage(): Storage
    {
        return $this->storage;
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
        $this->cache[$name] = $value;
        if ($store) {
            $this->storage->save($name, $value);
        }
        return true;
    }

    public function backup()
    {
        foreach ($this->cached as $name => $value) {
            if (!$value instanceof Condition || $name == '') {
                continue;
            }
            $this->storage->save($name, $value);
        }
    }

    public function read(string $name = '', bool $stored = true): Condition|array|null
    {
        if ($name == '') {
            if ($stored) {
                $this->cached = $this->storage->read();
            }
            return $this->cached;
        }
        $condition = @$this->cached[$name];
        if ($stored && $condition == null) {
            $condition = $this->storage->read($name);
        }
        return $condition;
    }

    public function delete(string $name = '', bool $stored = true): bool
    {
        if ($name == '') {
            $this->cached = [];
        } else {
            unset($this->cached[$name]);
        }
        if (!$stored) {
            return true;
        }
        return $this->storage->delete($name);
    }
}
