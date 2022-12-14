<?php

namespace Hilmy\FeatureControl;

use Hilmy\FeatureControl\Conditions\Condition;
use Hilmy\FeatureControl\Storages\Storage;

class Manager
{
    private array $registered;
    private Storage $storage;

    public function __construct(string $basePath = '')
    {
        $this->registered = [];
        $this->storage = new Storage($basePath);
    }

    public function storage(): Storage
    {
        return $this->storage;
    }

    public function registered(): array
    {
        return $this->registered;
    }

    public function set(
        string $name,
        bool $toggle = false,
        int $percentage = 0,
        int $start = 0,
        int $end = 0,
        array $whitelist = [],
    ): bool {
        if ($name == '') {
            return false;
        }
        $condition = new Condition($toggle, $percentage, $start, $end, $whitelist);
        return $this->register($name, $condition);
    }

    public function register(string $name, Condition $condition): bool
    {
        if ($name == '') {
            return false;
        }
        $this->registered[$name] = $condition;
        return true;
    }

    public function remove(string $name): bool
    {
        if ($name == '') {
            return false;
        }
        unset($this->registered[$name]);
        return true;
    }

    public function save(string $name): bool
    {
        if ($name == '') {
            return false;
        }
        $condition = @$this->registered[$name];
        if (empty($condition)) {
            return false;
        }
        return $this->storage->save($name, $condition);
    }

    public function backup()
    {
        foreach ($this->registered as $name => $condition) {
            $this->storage->save($name, $condition);
        }
    }

    public function load()
    {
    }

    public function read(string $name): Condition
    {
        if ($name == '') {
            return new Condition();
        }
        return $this->storage->read($name);
    }

    public function delete(string $name): bool
    {
        if ($name == '') {
            return false;
        }
        $this->remove($name);
        return $this->storage->delete($name);
    }
}
