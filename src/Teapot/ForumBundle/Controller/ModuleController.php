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
use Teapot\Base\ForumBundle\Form\CreateBoardType;

class ModuleController extends BaseController
{

    public function conciseListAction()
    {
        $params = array(
            'boards'         => $this->get('teapot.forum.board')->getBoards(false, false),
            'containerClass' => 'module list dark',
        );

        return $this->render('TeapotBaseForumBundle:Board:modules/conciseList.html.twig', $params);
    }

    public function listBoardsAction()
    {
        $params = array(
            'boards'         => $this->get('teapot.forum.board')->getBoards(false, false),
            'containerClass' => '',
        );

        return $this->render('TeapotBaseForumBundle:Board:modules/list.html.twig', $params);
    }

    public function topUsersAction()
    {
        $params = array(
            'containerClass' => '',
        );

        return $this->render('TeapotForumBundle:Modules:topUsers.html.twig', $params);
    }

    public function moderationListAction()
    {
        if ($this->get('teapot.forum.access_permission')->isModerator($this->getUser()) === false) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }

        $params = array(
            'containerClass' => '',
        );

        return $this->render('TeapotForumBundle:Moderation:modules/list.html.twig', $params);
    }

}