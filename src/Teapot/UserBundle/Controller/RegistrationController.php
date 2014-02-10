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

use Teapot\Base\UserBundle\Form\UserSignupType;
use Teapot\UserBundle\Entity\User;
use Teapot\UserBundle\Entity\UserGroup;

use Teapot\Base\UserBundle\Controller\RegistrationController as BaseController;

class RegistrationController extends BaseController
{

    public function signupAction()
    {
        $request = $this->getRequest();

        $user = new User();

        $form = $this->createForm(new UserSignupType(), $user);

        if ($request->getMethod() === 'POST') {
            $form->bind($request);
            if ($form->isValid() === true) {
                $em = $this->get('doctrine')->getManager();

                $groups = $em->getRepository('TeapotUserBundle:UserGroup')
                            ->findBy(array('role' => 'ROLE_USER'));

                $image = $this->get('teapot.image')->getById(1); // 1: default image

                $this->get('teapot.user')->setup(
                    $user->getUsername(),
                    $user->getEmail(),
                    $user->getPassword(),
                    $groups,
                    array($image)
                );

                $this->redirect("/");
            }
        }

        return $this->render('TeapotBaseUserBundle:Registration:signup.html.twig', array(
            'form' => $form->createView()
        ));
    }
}