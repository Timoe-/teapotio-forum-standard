<?php

/**
 * Copyright (c) Thomas Potaire
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @category   Teapot
 * @package    UserBundle
 * @author     Thomas Potaire
 */

namespace Teapot\UserBundle\Controller;

use Teapot\ImageBundle\Entity\Image;
use Teapot\ImageBundle\Form\ImageType;

use Teapot\UserBundle\Entity\User;
use Teapot\UserBundle\Entity\UserGroup;
use Teapot\UserBundle\Entity\UserSettings;
use Teapot\UserBundle\Form\UserDescriptionType;
use Teapot\UserBundle\Form\UserSettingsType;

use Symfony\Component\Security\Core\SecurityContext;

use Teapot\Components\Controller;

class SecurityController extends Controller
{
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        $title = $this->generateTitle("Login");

        $params = array(
            'page_title'    => $title,
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'login_error'   => $error,
        );

        if ($this->get('request')->isXmlHttpRequest() === true) {
            return $this->renderJson(array(
                'html'   => $this->renderView('TeapotUserBundle:Security:raw/login.html.twig', $params),
                'title'  => $title
            ));
        }

        return $this->render('TeapotUserBundle:Security:login.html.twig', $params);
    }
}
