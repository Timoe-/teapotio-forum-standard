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

namespace Teapot\UserBundle\Repository;

use Teapot\Base\UserBundle\Repository\UserRepository as BaseUserGroupRepository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Doctrine\Common\Collections\ArrayCollection;

class UserGroupRepository extends BaseUserGroupRepository
{

}