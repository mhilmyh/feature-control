<?php

namespace Hilmy\FeatureControl\Storages;

class File
{
    private string $basePath = '';
    private string $filename = 'feat.txt';
    private int $permission = 0764;

    public function __construct(string $basePath = '')
    {
        $this->basePath = $basePath;
    }

    public function dirPath(string $dirname): string
    {
        return $this->basePath . '/' . $dirname;
    }

    public function fullPath(string $dirname): string
    {
        return $this->dirPath($dirname)  . '/' . $this->filename;
    }

    public function ensureDir(string $dirname): bool
    {
        if ($dirname == '') {
            return false;
        }
        $dirPath = $this->dirPath($dirname);
        if (is_dir($dirPath)) {
            return true;
        }
        return mkdir($dirPath, $this->permission, true);
    }

    public function set(string $dirname = '', string $content = ''): bool
    {
        if ($dirname == '') {
            return false;
        }
        $this->ensureDir($dirname);
        return file_put_contents($this->fullPath($dirname), $content) != false;
    }

    public function get(string $dirname): string
    {
        $this->ensureDir($dirname);
        return file_get_contents($this->fullPath($dirname)) ?? '';
    }

    public function del(string $dirname = ''): bool
    {
        if ($dirname == '') {
            return false;
        }
        return unlink($this->fullPath($dirname));
    }
}
