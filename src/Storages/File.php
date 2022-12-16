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

    public function path(): string
    {
        return $this->base;
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
        $parent = implode('/', [$this->base, $dir]);
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
        return file_get_contents(implode('/', [$this->base, $dir, $this->fname])) ?? '';
    }

    public function del(string $dir = ''): bool
    {
        if ($dir == '') {
            return false;
        }
        $path = implode('/', [$this->base, $dir, $this->fname]);
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
