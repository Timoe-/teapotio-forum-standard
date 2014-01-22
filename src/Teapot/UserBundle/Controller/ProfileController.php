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

use Teapot\UserBundle\Entity\UserSettings;
use Teapot\UserBundle\Form\UserDescriptionType;
use Teapot\UserBundle\Form\UserSettingsType;

use Teapot\Components\Controller;

class ProfileController extends Controller
{
    public function indexAction($userSlug, $userId)
    {
        $user = $this->get('teapot.user')
                     ->find($userId);

        if ($user === null) {
            throw $this->createNotFoundException();
        }

        if ($userSlug !== $user->getSlug()) {
            return $this->redirect(
                $this->generateUrl('TeapotBaseUserBundle_profile', array(
                    'userSlug' => $user->getSlug(),
                    'userId'   => $user->getId(),
                ))
            );
        }

        $latestTopics = $this->get('teapot.forum.topic')->getLatestTopicsByUser($user, 0, 5);

        $messagesPerPage = $this->get('teapot.forum')->getTotalMessagesPerPage();

        $title = $this->generateTitle("%username%'s profile", array('%username%' => $user->getUsername()));

        // Provide a new notice message to explain what's going on the page
        // A moderator or an admin should be able to modify a user info
        $infoNotices = array();
        $infoNoticeLinks = array();
        if ($this->get('teapot.forum.access_permission')->isModerator($this->getUser()) === true) {
            $infoNotices[] = $this->get('translator')->trans('You.have.enough.rights.to.modify.this.user');

            $infoNoticeLinkActionPath = $this->generateUrl(
                'TeapotBaseUserBundle_settings',
                array(
                    'userSlug' => $user->getSlug(),
                    'userId'   => $user->getId(),
                )
            );

            $infoNoticeLinkActionLabel = $this->get('translator')->trans('Edit');

            $infoNoticeLinks[] = array(
                'path'   => $infoNoticeLinkActionPath,
                'label'  => $infoNoticeLinkActionLabel,
            );
        }

        $params = array(
            'user'                => $user,
            'latest_topics'       => $latestTopics,
            'messages_per_page'   => $messagesPerPage,
            'page_title'          => $title,
            'info_notices'        => $infoNotices,
            'info_notice_links'   => $infoNoticeLinks,
        );

        if ($this->get('request')->isXmlHttpRequest() === true) {
            return $this->renderJson(array(
                'html'   => $this->renderView('TeapotUserBundle:Profile:raw/index.html.twig', $params),
                'title'  => $title
            ));
        }

        return $this->render('TeapotUserBundle:Profile:index.html.twig', $params);
    }

    public function settingsAction($userSlug, $userId)
    {
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') === false) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }

        $user = $this->get('teapot.user')
                     ->find($userId);

        if ($user === null) {
            throw $this->createNotFoundException();
        }

        $isCurrentUserModerator = $this->get('teapot.forum.access_permission')->isModerator($this->getUser());
        $isCurrentUserAdmin = $this->get('teapot.forum.access_permission')->isAdmin($this->getUser());

        if ($isCurrentUserAdmin === false && $isCurrentUserModerator === false
            && $user->getId() !== $this->get('security.context')->getToken()->getUser()->getId()) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }

        if ($userSlug !== $user->getSlug()) {
            return $this->redirect(
                $this->generateUrl('TeapotBaseUserBundle_profile', array(
                    'userSlug' => $user->getSlug(),
                    'userId'   => $user->getId(),
                ))
            );
        }

        $settings = $user->getSettings();
        if ($settings === null) {
            $settings = new UserSettings();
        }

        $infoNotices = array();
        if ($user->getId() !== $this->getUser()->getId()) {
            // Provide a new notice message to explain what's going on the page
            // A moderator should be able to modify a user info
            if ($isCurrentUserModerator === true) {
                $infoNotices[] = $this->get('translator')->trans('You.have.access.to.this.page.because.you.were.granted.some.special.rights');
            }

            // Provide a new notice message to explain what's going on the page
            // Also add extra forms for extended
            if ($isCurrentUserAdmin === true) {
                $infoNotices[] = $this->get('translator')->trans('As.an.admin.you.have.access.to.extra.tools');
            }
        }

        $formImage = $this->createForm(new ImageType(), new Image());
        $formDescription = $this->createForm(new UserDescriptionType(), $user);
        $formSettings = $this->createForm(new UserSettingsType(), $settings);

        $title = $this->generateTitle("%username%'s settings", array('%username%' => $user->getUsername()));

        $params = array(
            'user'              =>  $user,
            'formImage'         =>  $formImage->createView(),
            'formDescription'   =>  $formDescription->createView(),
            'formSettings'      =>  $formSettings->createView(),
            'page_title'        =>  $title,
            'info_notices'      =>  $infoNotices,
        );

        if ($this->get('request')->isXmlHttpRequest() === true) {
            return $this->renderJson(array(
                'html'   => $this->renderView('TeapotUserBundle:Profile:raw/settings.html.twig', $params),
                'title'  => $title
            ));
        }

        return $this->render('TeapotUserBundle:Profile:settings.html.twig', $params);
    }
}
