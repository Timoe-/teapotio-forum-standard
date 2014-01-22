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

class CacheService implements CacheServiceInterface
{
    protected $container;
    protected $engine = null;

    public function __construct($container, $engine)
    {
        $this->container = $container;

        if (is_object($engine) === true) {
            $this->engine = $engine;
        } else {
            switch ($engine) {
                case 'filesystem':
                    $this->engine = $this->container->get('teapot.cache.filesystem');
                    break;
            }
        }
    }

    /**
     * {@inherit}
     */
    public function has($key)
    {
        return $this->engine->has($key);
    }

    /**
     * {@inherit}
     */
    public function set($key, $value)
    {
        $this->engine->set($key, $value);
    }

    /**
     * {@inherit}
     */
    public function get($key)
    {
        return $this->engine->get($key);
    }

    /**
     * {@inherit}
     */
    public function remove($key)
    {
        $this->engine->remove($key);
    }
}