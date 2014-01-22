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

namespace Teapot\UserBundle\Service;

use Teapot\UserBundle\Entity\UserGroup;

use Teapot\Base\UserBundle\Service\UserGroupService as BaseService;

class UserGroupService extends BaseService {

    public function createUserGroup()
    {
        return new UserGroup();
    }

    public function setup()
    {
        $adminGroup = $this->createUserGroup();
        $adminGroup->setName('Admin');
        $adminGroup->setRole('ROLE_ADMIN');

        $this->save($adminGroup);

        $userGroup = $this->createUserGroup();
        $userGroup->setName('User');
        $userGroup->setRole('ROLE_USER');

        $this->save($userGroup);

        return array($adminGroup, $userGroup);
    }

}