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

use Teapot\ImageBundle\Form\ImageType;
use Teapot\ImageBundle\Entity\Image;

use Teapot\UserBundle\Entity\UserSettings;
use Teapot\UserBundle\Form\UserSettingsType;
use Teapot\UserBundle\Form\UserDescriptionType;

use Teapot\Components\Controller;

class UserController extends Controller
{

    public function addImageAction()
    {
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') === false) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }

        $request = $this->get('request');

        $image = new Image();
        $form = $this->createForm(new ImageType(), $image);

        if ($request->getMethod() === 'POST') {
            $form->bind($request);
            if ($form->isValid() === true) {

                $em = $this->get('doctrine')
                           ->getEntityManager();

                $image->setUser(
                    $this->get('security.context')->getToken()->getUser()
                );

                $em->persist($image);
                $em->flush();

                $this->get('security.context')
                     ->getToken()
                     ->getUser()
                        ->addAvatar($image)
                        ->setDefaultAvatar($image);

                $em->persist($this->get('security.context')->getToken()->getUser());
                $em->flush();

                if ($request->isXmlHttpRequest() === true) {
                    return $this->renderJson(array('success' => 1, 'message' => $this->get('translator')->trans('Saved')));
                }
            }
        }

        if ($request->isXmlHttpRequest() === true) {
            return $this->renderJson(array('success' => 0, 'message' => $this->get('translator')->trans('Unsaved')));
        }

        return $this->redirect(
            $request->headers->get('referer')
        );
    }

    public function setDefaultImageAction($imageId)
    {
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') === false) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }

        $request = $this->get('request');

        $user = $this->get('security.context')->getToken()->getUser();

        $this->get('teapot.user')->setDefaultAvatarFromAvatars($user, (int)$imageId);

        if ($request->isXmlHttpRequest() === true) {
            return $this->renderJson(array('success' => 1, 'message' => $this->get('translator')->trans('Saved')));
        }

        return $this->redirect(
            $request->headers->get('referer')
        );
    }

    public function setDescriptionAction()
    {
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') === false) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }

        $request = $this->get('request');

        $user = $this->get('security.context')->getToken()->getUser();

        $form = $this->createForm(new UserDescriptionType(), $user);

        if ($request->getMethod() === 'POST') {
            $form->bind($request);
            if ($form->isValid() === true) {

                $em = $this->get('doctrine')
                           ->getEntityManager();

                $em->persist($user);
                $em->flush();

                if ($request->isXmlHttpRequest() === true) {
                    return $this->renderJson(array('success' => 1, 'message' => $this->get('translator')->trans('Saved')));
                }
            }
        }

        if ($request->isXmlHttpRequest() === true) {
            return $this->renderJson(array('success' => 0, 'message' => $this->get('translator')->trans('Unsaved')));
        }

        return $this->redirect(
            $request->headers->get('referer')
        );
    }

    public function setSettingsAction()
    {
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') === false) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }

        $request = $this->get('request');

        $user = $this->get('security.context')->getToken()->getUser();

        if ($user->getSettings() === null) {
            $user->setSettings(new UserSettings());
        }

        $form = $this->createForm(new UserSettingsType(), $user->getSettings());

        if ($request->getMethod() === 'POST') {
            $form->bind($request);
            if ($form->isValid() === true) {

                $em = $this->get('doctrine')
                           ->getEntityManager();

                $user->getSettings()->setUser($user);

                if ($user->getSettings()->getBackgroundImage() !== null) {
                    $user->getSettings()->getBackgroundImage()->setUser($user);
                    $em->persist($user->getSettings()->getBackgroundImage());
                }

                $em->persist($user->getSettings());
                $em->flush();

                if ($request->isXmlHttpRequest() === true) {
                    return $this->renderJson(array('success' => 1, 'message' => $this->get('translator')->trans('Saved')));
                }
            }
        }

        if ($request->isXmlHttpRequest() === true) {
            return $this->renderJson(array('success' => 0, 'message' => $this->get('translator')->trans('Unsaved')));
        }

        return $this->redirect(
            $request->headers->get('referer')
        );
    }

}