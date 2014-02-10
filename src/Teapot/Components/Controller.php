<?php

/**
 * Copyright (c) Thomas Potaire
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @category   Teapot
 * @package    Components
 * @author     Thomas Potaire
 */

namespace Teapot\Components;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;

class Controller extends BaseController {


    /**
     * Renders json response.
     *
     * @param  array  $data   an array of data to send
     *
     * @return Response  A Response instance
     */
    public function renderJson(array $data = array())
    {
        $response = new JsonResponse();
        $response->setData($data);

        return $response;
    }

    /**
     * Given some HTML send a response.
     *
     * @param string   $html   The view name
     *
     * @return Response A Response instance
     */
    public function renderHtml($html)
    {
        $response = new Response();
        $response->setContent($html);

        return $response;
    }


    /**
     * Generate a title and translate etc etc
     *
     * @param  string   $key
     * @param  array    $params
     *
     * @return string
     */
    public function generateTitle($key, $params = array())
    {
        return $this->get('teapot.site')->generateTitle($key, $params);
    }

}