<?php

namespace Hilmy\FeatureControl;

use Hilmy\FeatureControl\Storages\File;

class Storage
{
    private File $file;

    public function __construct(string $basePath = '')
    {
        $this->file = new File($basePath);
    }

    public function save(string $name, Condition $condition): bool
    {

        return $this->file->set($name);
    }

    public function load(string $name): string
    {
        return $this->file->get($name);
    }

    public function delete(string $name): bool
    {
        return $this->file->remove($name);
    }
}
