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
use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;

use Symfony\Component\HttpFoundation\JsonResponse;

class Controller extends BaseController {


    /**
     * Renders json response.
     *
     * @param string   $view       The view name
     *
     * @return Response A Response instance
     */
    public function renderJson(array $data = array())
    {
        $response = new JsonResponse();
        $response->setData($data);

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
        return $this->get('translator')->trans($key, $params) .' - '. $this->container->getParameter('forum_title');
    }

}