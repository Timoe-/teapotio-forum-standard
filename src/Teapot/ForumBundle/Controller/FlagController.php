<?php

/**
 * Copyright (c) Thomas Potaire
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @category   Teapot
 * @package    ForumBundle
 * @author     Thomas Potaire
 */

namespace Teapot\ForumBundle\Controller;

use Teapot\ForumBundle\Entity\Board;
use Teapot\BaseForumBundle\Form\CreateBoardType;

class FlagController extends BaseController
{

    public function flagListAction()
    {
        if ($this->get('teapot.forum.access_permission')->isModerator($this->getUser()) === false) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }

        $params = array(
            'containerClass' => '',
        );

        return $this->render('TeapotForumBundle:Flag:modules/list.html.twig', $params);
    }

    public function ignoreAction($flagId)
    {
        if ($this->get('teapot.forum.access_permission')->isModerator($this->getUser()) === false) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }

        $flag = $this->container
                     ->get('teapot.forum.flag')
                     ->getById($flagId);

        if ($flag !== null) {
            $this->container
                 ->get('teapot.forum.flag')
                 ->ignore($flag, $this->getUser());
        }

        if ($this->get('request')->isXmlHttpRequest() === true) {
            return $this->renderJson(array('success' => 1));
        }

        return $this->redirect(
            $this->get('request')->headers->get('referer')
        );
    }

    public function deleteAction($flagId)
    {
        if ($this->get('teapot.forum.access_permission')->isModerator($this->getUser()) === false) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }

        $flag = $this->container
                     ->get('teapot.forum.flag')
                     ->getById($flagId, $this->getUser());

        if ($flag !== null) {
            $this->container
                 ->get('teapot.forum.flag')
                 ->delete($flag);
        }

        if ($this->get('request')->isXmlHttpRequest() === true) {
            return $this->renderJson(array('success' => 1));
        }

        return $this->redirect(
            $this->get('request')->headers->get('referer')
        );
    }

}