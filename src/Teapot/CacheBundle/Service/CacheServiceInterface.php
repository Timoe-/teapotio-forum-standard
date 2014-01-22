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

interface CacheServiceInterface
{
    /**
     * Returns whether a value for the key exists or not
     *
     * @param  string  $key
     *
     * @return boolean
     */
    public function has($key);

    /**
     * Set a value in cache based on a key
     *
     * @param string  $key
     * @param string  $value
     */
    public function set($key, $value);

    /**
     * Get a value from cache based on a given key
     *
     * @param  string $key
     *
     * @return string
     */
    public function get($key);

    /**
     * Remove a value from cache based on a given key
     *
     * @param  string  $key
     */
    public function remove($key);

}