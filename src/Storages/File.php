<?php

namespace Hilmy\FeatureControl\Storages;

class File
{
    private string $basePath = '';

    public function __construct(string $basePath = '')
    {
        $this->basePath = $basePath;
    }

    public function relativePath(string $dirname): string
    {
        return $this->basePath . '/' . $dirname;
    }

    public function set(string $dirname = '', string $content = ''): bool
    {
        if ($dirname == '') {
            return false;
        }
        return file_put_contents($this->relativePath($dirname), $content) != false;
    }

    public function get(string $dirname): string
    {
        return file_get_contents($this->relativePath($dirname)) ?? '';
    }

    public function del(string $dirname = ''): bool
    {
        if ($dirname == '') {
            return false;
        }
        return unlink($this->relativePath($dirname));
    }
}
