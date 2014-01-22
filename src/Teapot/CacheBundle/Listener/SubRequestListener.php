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

namespace Teapot\CacheBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class SubRequestListener
{
    protected $container;

    public function __construct (ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernel::MASTER_REQUEST === $event->getRequestType()) {
            // don't do anything if it's the master request
            return;
        }

        $response = $this->container->get('teapot.cache.request')->getResponse();

        // If response was cached return it
        if ($response !== false && $response !== null) {
            $event->setResponse($response);
        }
    }
}