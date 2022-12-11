<?php

namespace Hilmy\FeatureControl\Storages;

class File
{
    private string $basePath = '';

    public function __construct(string $basePath = '')
    {
        $this->basePath = $basePath;
    }

    public function relativePath(string $filename): string
    {
        return $this->basePath . '/' . $filename;
    }

    public function set(string $filename = '', string $content = ''): bool
    {
        if ($filename == '') {
            return false;
        }
        return file_put_contents($this->relativePath($filename), $content) != false;
    }

    public function get(string $filename): string
    {
        return file_get_contents($this->relativePath($filename)) ?? '';
    }

    public function del(string $filename = ''): bool
    {
        if ($filename == '') {
            return false;
        }
        return unlink($this->relativePath($filename));
    }
}
