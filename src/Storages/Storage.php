<?php

namespace Hilmy\FeatureControl\Storages;

use Hilmy\FeatureControl\Conditions\Condition;

class Storage
{
    private File $file;
    private string $pattern;

    public function __construct(string $base = '')
    {
        $this->file = new File($base);
        $this->pattern = '/^(' . $base . ')\/?/';
    }

    public function base(): string
    {
        return $this->file->fullpath(base: true, dir: false, fname: false);
    }

    public function set(string $name, Condition $condition): bool
    {
        if ($name == '') {
            return false;
        }
        return $this->file->set($name, $condition->toString());
    }

    public function all(string $dir = ''): array
    {
        $result = [];
        $files = $this->file->scandir($dir);
        foreach ($files as $fname) {
            $subdir = implode('/', [$dir, $fname]);
            if ($this->file->filename($fname) && is_file($subdir)) {
                $name = preg_replace($this->pattern, '', $dir, 1);
                $result[$dir] = $this->get($name);
                continue;
            }
            if (!is_dir($subdir)) {
                continue;
            }
            $child = $this->all($subdir);
            foreach ($child as $key => $condition) {
                $name = preg_replace($this->pattern, '', $key, 1);
                $result[$name] = $condition;
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
