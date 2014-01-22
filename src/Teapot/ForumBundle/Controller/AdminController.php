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

class AdminController extends BaseController
{

    public function permissionsAction($groupId = null)
    {
        $formName = 'board_permissions';

        $groups = $this->get('teapot.user.group')->getAllGroups();

        $group = null;
        if ($groupId !== null) {
            $group = $this->get('teapot.user.group')->getById($groupId);

            if ($group === null) {
                return $this->redirect($this->generateUrl('ForumAdminPermissions'));
            }
        }

        if ($this->get('request')->isMethod('POST') === true) {
            $postData = $this->get('request')->request;
            $this->container
                 ->get('teapot.forum.access_permission')
                 ->setPermissionsOnBoardsFromPostData($groupId, $postData->get('board_permissions'));
        }

        $title = $this->generateTitle('Manage.group.permissions');

        $params = array(
            'groups'     => $groups,
            'group'      => $group,
            'form_name'  => $formName,
            'page_title' => $title,
        );

        return $this->render('TeapotBaseForumBundle:Admin:permissions.html.twig', $params);
    }

}