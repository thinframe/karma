<?php

namespace ThinFrame\Karma\Manager;

/**
 * Class AssetsManager
 * @package ThinFrame\Karma\Manager
 * @since   0.3
 */
class AssetsManager
{
    /**
     * @var array
     */
    private $roots = [];

    /**
     * @var array
     */
    private $assets = [];

    /**
     * Add a assets root
     *
     * @param string $name
     * @param string $path
     */
    public function addRoot($name, $path)
    {
        $this->roots[$name] = $path;
    }

    /**
     * Get roots
     * @return array
     */
    public function getRoots()
    {
        return $this->roots;
    }

    /**
     * Get root path
     *
     * @param string $name
     *
     * @return null|string
     */
    public function getRoot($name)
    {
        if (!isset($this->roots[$name])) {
            return null;
        }

        return $this->roots[$name];
    }

    /**
     * Add asset
     *
     * @param string $rootName
     * @param string $path
     */
    public function addAsset($rootName, $path)
    {
        if (!isset($this->assets[$rootName])) {
            $this->assets[$rootName] = [];
        }
        $this->assets[$rootName][] = $path;
    }

    /**
     * Get assets
     * @return array
     */
    public function getAssets()
    {
        return $this->assets;
    }
}