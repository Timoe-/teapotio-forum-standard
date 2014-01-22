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

use Symfony\Component\HttpFoundation\Response;

class RequestService
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Returns a Response object if request has been cached
     * Returns false if no view was found in view
     * Returns null if no cache rules has been set
     *
     * @return Response|false|null
     */
    public function getResponse()
    {
        if ($this->isRequestValid() === false) {
            return null;
        }

        $response = $this->getFromCache($this->getCacheKey());

        if ($response === false) {
            return false;
        }

        return $this->unserializeResponse($response);
    }

    /**
     * Set the response in cache
     *
     * @param Response $response
     */
    public function setResponse(Response $response)
    {
        if ($this->isRequestValid() === false) {
            return null;
        }

        $now = new \DateTime();

        // If response has not expired, don't proceed further
        if ($response->getExpires() < $now) {
            return null;
        }

        $response->setExpires($this->getCacheExpires());

        $response = $this->serializeResponse($response);

        $this->setInCache($this->getCacheKey(), $response);
    }

    /**
     * Returns whether the current request is cacheable
     *
     * @return boolean
     */
    protected function isRequestValid()
    {
        if ($this->container->get('request')->attributes->get('_locale') === null
            || $this->container->get('request')->attributes->get('_controller') === null) {
            return false;
        }

        $cacheKey = $this->getCacheKey();

        if ($cacheKey === null) {
            return false;
        }

        return true;
    }

    /**
     * Serialize the response into a JSON string
     *
     * @param  Response $response
     *
     * @return string
     */
    protected function serializeResponse(Response $response)
    {
        $data = array(
            'content'       => $response->getContent(),
            'status_code'   => $response->getStatusCode(),
            'charset'       => $response->getCharset(),
            'headers'       => $response->headers->all()
        );

        return json_encode($data);
    }

    /**
     * Unserialize a response and return it
     *
     * @param  string  $data  the JSON serialized response
     *
     * @return Response
     */
    protected function unserializeResponse($data)
    {
        $data = json_decode($data, true);

        $response = new Response();
        $response->setContent($data['content']);
        $response->setStatusCode($data['status_code']);
        $response->setCharset($data['charset']);
        $response->headers->replace($data['headers']);

        return $response;
    }

    /**
     * Set the serialized response in cache
     *
     * @param string  $cacheKey
     * @param string  $serializedResponse
     */
    protected function setInCache($cacheKey, $serializedResponse)
    {
        $this->container->get('teapot.cache')->set($cacheKey, $serializedResponse);
    }

    /**
     * Returns the serialized response for the given cache key otherwise
     * returns false if the content hasn't been cached
     *
     * @param  string  $cacheKey
     *
     * @return string|false
     */
    protected function getFromCache($cacheKey)
    {
        return $this->container->get('teapot.cache')->get($cacheKey);
    }

    /**
     * Returns the cache key for the current request
     *
     * @return string
     */
    protected function getCacheKey()
    {
        $controller = $this->container->get('request')->attributes->get('_controller');
        $locale = $this->container->get('request')->attributes->get('_locale');

        $user = null;
        $cacheSuffix = null;

        if ($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED') === true) {
            $user = $this->container->get('security.context')->getToken()->getUser();
        }

        switch ($controller) {
            case 'TeapotBaseForumBundle:Module:listBoards':
                if ($user === null) {
                    $cacheSuffix = 'logged-out';
                } else {
                    $groupIds = array();
                    foreach ($user->getGroups() as $group) {
                        $groupIds[] = $group->getId();
                    }
                    sort($groupIds);
                    $cacheSuffix = implode('-', $groupIds);
                }
                break;
            case 'TeapotBaseForumBundle:Module:moderationList':
            case 'TeapotBaseForumBundle:Flag:flagList':
                if ($user === null && $this->container->get('teapot.forum.access_permission')->isModerator($user) === false) {
                    return null;
                }
                break;
            case 'TeapotBaseForumBundle:Module:topUsers':
                break;
            default:
                return null;
        }

        if ($cacheSuffix !== null) {
            return $controller . ':' . $locale . ':' . $cacheSuffix;
        } else {
            return $controller . ':' . $locale;
        }
    }

    /**
     * Returns when a cache will expire
     *
     * @return \DateTime
     */
    protected function getCacheExpires()
    {
        $controller = $this->container->get('request')->attributes->get('_controller');

        switch ($controller) {
            case 'TeapotBaseForumBundle:Module:listBoards':
                $lifetime = 600; // 10 minutes
                break;
            case 'TeapotBaseForumBundle:Module:moderationList':
            case 'TeapotBaseForumBundle:Flag:flagList':
                $lifetime = 120; // 2 minutes
                break;
            case 'TeapotBaseForumBundle:Module:topUsers':
                $lifetime = 3600; // 1 hour
                break;
            default:
                $lifetime = 7200; // 2 hours
                break;
        }

        return new \DateTime('@' . (time() + $lifetime));
    }
}