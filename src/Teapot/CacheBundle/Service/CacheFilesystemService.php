<?php

/**
 * Copyright (c) Thomas Potaire
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @category   Teapot
 * @package    CacheBundle
 * @author     Thomas Potaire
 */

namespace Teapot\CacheBundle\Service;

class CacheFilesystemService implements CacheServiceInterface
{
    protected $cacheDir;

    /**
     * Holds cache data for multiple requests
     *
     * @var array
     */
    protected $caches = array();

    public function __construct($cacheDir)
    {
        $this->cacheDir = $cacheDir;
    }

    /**
     * Returns whether a cache value for the given key
     *
     * @param  string  $key
     *
     * @return boolean
     */
    public function has($key)
    {
        return file_exists($this->getPath($key));
    }

    /**
     * Set a cache value based on a key
     * You can organize your cache file by using colons
     *
     * @param  string  $key
     * @param  string  $value
     */
    public function set($key, $value)
    {
        if (is_dir($this->getDirectoryPath($key)) === false
            && false === @mkdir($this->getDirectoryPath($key), 0777, true)) {
            throw new \RuntimeException(sprintf('Unable to create cache directory "%s"', $this->getDirectoryPath($key)));
        }

        if (false === @file_put_contents($this->getPath($key), $value)) {
            throw new \RuntimeException(sprintf('Unable to write cache file "%s"', $this->getPath($key)));
        }
    }

    /**
     * Get a cache value based on a key
     *
     * @param  string  $key
     *
     * @return array
     */
    public function get($key)
    {
        if (array_key_exists($key, $this->caches) === true) {
            return $this->caches[$key];
        }

        $path = $this->getPath($key);

        if ($this->has($key) === false) {
            return false;
        }

        return file_get_contents($path);
    }

    /**
     * Remove a cache file based on a given key
     *
     * @param  string  $key
     *
     * @return boolean
     */
    public function remove($key)
    {
        $path = $this->getPath($key);

        if (file_exists($path) && false === @unlink($path)) {
            throw new \RuntimeException(sprintf('Unable to remove cache file "%s"', $path));
        }
    }

    /**
     * Transform a key to a usable path
     *
     * @param  string  $key
     *
     * @return string
     */
    protected function transformKey($key)
    {
        return str_replace(':', '/', $key);
    }

    /**
     * Given a key return the proper path
     *
     * @param  string  $key
     *
     * @return string
     */
    protected function getPath($key)
    {
        return $this->cacheDir . '/' . $this->transformKey($key) . '.cache';
    }

    /**
     * Given a key return the directory's path
     *
     * @param  string  $key
     *
     * @return string
     */
    protected function getDirectoryPath($key)
    {
        $path = explode('/', $this->getPath($key));
        array_pop($path);
        return implode('/', $path);
    }
}