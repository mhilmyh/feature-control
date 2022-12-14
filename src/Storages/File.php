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

    private function dirPath(string $dirname): string
    {
        return $this->basePath . '/' . $dirname;
    }

    private function fullPath(string $dirname): string
    {
        return $this->dirPath($dirname)  . '/' . $this->filename;
    }

    private function ensureDir(string $dirname): bool
    {
        if ($dirname == '') {
            return false;
        }
        if (is_dir($dirname)) {
            return true;
        }
        return mkdir($dirname, $this->permission, true);
    }

    private function recursiveDir(string $dirname): array
    {
        $result = [];
        $items = scandir($dirname, SCANDIR_SORT_DESCENDING);
        array_pop($items);
        array_pop($items);
        foreach ($items as $item) {
            $path = $dirname . '/' . $item;
            if (!is_dir($path)) {
                continue;
            }
            $recursived = $this->recursiveDir($path);
            if (!count($recursived)) {
                $result[] = $item;
                continue;
            }
            foreach ($recursived as $recursive) {
                $result[] = $item . '/' . $recursive;
            }
        }
        return $result;
    }

    public function set(string $dirname = '', string $content = ''): bool
    {
        if ($dirname == '') {
            return false;
        }
        $this->ensureDir($this->dirPath($dirname));
        return file_put_contents($this->fullPath($dirname), $content) != false;
    }

    public function get(string $dirname = ''): string|array
    {
        $this->ensureDir($this->dirPath($dirname));
        if ($dirname == '') {
            return $this->recursiveDir($this->basePath);
        }
        return file_get_contents($this->fullPath($dirname)) ?? '';
    }

    public function del(string $dirname = ''): bool
    {
        if ($dirname == '') {
            return unlink($this->basePath);
        }
        return unlink($this->dirPath($dirname));
    }
}
