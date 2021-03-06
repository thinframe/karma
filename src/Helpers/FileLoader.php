<?php

/**
 * /src/Helpers/FileLoader.php
 *
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Karma\Helpers;

use Stringy\StaticStringy;

/**
 * FileLoader Helper
 *
 * @package ThinFrame\Karma\Helpers
 * @since   0.1
 */
class FileLoader
{
    /**
     * Require all php files from provided directory
     *
     * @param      $path
     * @param bool $recursive
     */
    public static function doRequire($path, $recursive = true)
    {
        if (is_dir($path) && $recursive) {
            $children = scandir($path);
            foreach ($children as $child) {
                if ($child == '.' || $child == '..') {
                    continue;
                }

                self::doRequire($path . DIRECTORY_SEPARATOR . $child);
            }
        } elseif (is_file($path) && StaticStringy::endsWith($path, '.php')) {
            require_once $path;
        }
    }
}