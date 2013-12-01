<?php
namespace ThinFrame\Karma\Helpers;

class FileLoader
{
    public static function doRequire($path, $recursive = true)
    {
        if (is_dir($path) && $recursive) {
            $children = scandir($path);
        }
    }
}