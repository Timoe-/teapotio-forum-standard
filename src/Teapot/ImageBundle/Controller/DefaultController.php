<?php

/**
 * Copyright (c) Thomas Potaire
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @category   Teapot
 * @package    ImageBundle
 * @author     Thomas Potaire
 */

namespace Teapot\ImageBundle\Controller;

use Teapot\Components\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('TeapotImageBundle:Default:index.html.twig', array('name' => $name));
    }
}
