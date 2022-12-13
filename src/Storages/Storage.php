<?php

namespace Hilmy\FeatureControl\Storages;

use Hilmy\FeatureControl\Conditions\Condition;

class Storage
{
    private File $file;

    public function __construct(string $basePath = '')
    {
        $this->file = new File($basePath);
    }

    public function save(string $name, Condition $condition): bool
    {

        return $this->file->set($name, $condition->toString());
    }

    public function load(string $name): string
    {
        return $this->file->get($name);
    }

    public function read(string $name): Condition
    {
        $content = $this->load($name);
        $condition = new Condition();
        $condition->fromString($content);
        return $condition;
    }

    public function delete(string $name): bool
    {
        return $this->file->del($name);
    }
}
