<?php

namespace Hilmy\FeatureControl\Storages;

use Hilmy\FeatureControl\Conditions\Condition;

class Storage
{
    private File $file;

    public function __construct(string $base = '')
    {
        $this->file = new File($base);
    }

    public function base(): string
    {
        return $this->file->path();
    }

    public function set(string $name, Condition $condition): bool
    {
        if ($name == '') {
            return false;
        }
        return $this->file->set($name, $condition->toString());
    }

    public function all(string $dir): array
    {
        $result = [];
        $files = $this->file->scandir($dir);
        foreach ($files as $fname) {
            $path = $dir . '/' . $fname;
            if ($this->file->filename($fname) && is_file($path)) {
                $result[$dir] = $this->get($dir);
            }
            if (!is_dir($path)) {
                continue;
            }
            $child = $this->all($path);
            foreach ($child as $key => $condition) {
                $result[$key] = $condition;
            }
        }
        return $result;
    }


    public function get(string $name = ''): Condition|null
    {
        if ($name == '') {
            return null;
        }
        $content = $this->file->get($name);
        if ($content == '') {
            return null;
        }
        $condition = new Condition();
        $condition->fromString($content);
        return $condition;
    }

    public function delete(string $name): bool
    {
        if ($name == '') {
            return false;
        }
        return $this->file->del($name);
    }
}
