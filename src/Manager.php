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

    public function delete(string $name): bool
    {
        if ($name == '') {
            return false;
        }
        return $this->storage->delete($name);
    }
}
