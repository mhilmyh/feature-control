<?php

namespace Hilmy\FeatureControl\Storages;

class File
{
    private int $permission = 0764;
    private string $base = '';
    private string $fname = '';

    public function __construct(string $base = '', string $fname = 'feat.txt')
    {
        $this->base = $base;
        $this->fname = $fname;
    }

    public function fullpath(string $dirpath = '', $base = true, $dir = true, $fname = true): string
    {
        if (strlen($dirpath) >= strlen($this->base) && strpos($dirpath, $this->base) != false) {
            return $dirpath;
        }
        $array = [];
        if ($base) {
            $array[] = $this->base;
        }
        if ($dir) {
            $array[] = $dirpath;
        }
        if ($fname) {
            $array[] = $this->fname;
        }
        $path = implode('/', $array);
        return $path;
    }

    public function filename(string $fname = ''): string|bool
    {
        if ($fname == '') {
            return $this->fname;
        }
        return $this->fname == $fname;
    }

    public function scandir(string $dir): array
    {
        $files = scandir($dir, SCANDIR_SORT_DESCENDING);
        array_pop($files);
        array_pop($files);
        return $files;
    }

    public function set(string $dir = '', string $content = ''): bool
    {
        if ($dir == '') {
            return false;
        }
        $parent = $this->fullpath($dir, fname: false);
        if (!is_dir($parent) && !mkdir($parent, $this->permission, true)) {
            return false;
        }
        return file_put_contents(implode('/', [$parent, $this->fname]), $content) != false;
    }

    public function get(string $dir = ''): string
    {
        if ($dir == '') {
            return '';
        }
        return file_get_contents($this->fullpath($dir)) ?? '';
    }

    public function del(string $dir = ''): bool
    {
        if ($dir == '') {
            return false;
        }
        $path = $this->fullpath($dir);
        if (is_file($path) && !unlink($path)) {
            return false;
        }
        for ($i = strlen($path) - 1; $i > 0; $i--) {
            if ($path[$i] != '/') {
                continue;
            }
            $sub = substr($path, 0, $i);
            if (!is_dir($sub)) {
                break;
            }
            $files = scandir($sub);
            if (count($files) > 2) {
                break;
            }
            if (!rmdir($sub)) {
                break;
            }
        }
        return true;
    }
}
