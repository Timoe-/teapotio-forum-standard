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

namespace Teapot\ForumBundle\Service;

use Teapot\ForumBundle\Entity\UserStat;
use Teapot\Base\ForumBundle\Service\UserStatService as BaseUserStatService;

class UserStatService extends BaseUserStatService
{
    public function createUserStat()
    {
        return new UserStat();
    }
}