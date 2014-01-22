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

namespace Teapot\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Teapot\Base\ForumBundle\Entity\UserStat as BaseUserStat;

use Teapot\Base\ForumBundle\Entity\UserStatInterface;

/**
 * Teapot\ForumBundle\Entity\UserStat
 *
 * @ORM\Table(name="`forum_user_stat`")
 * @ORM\Entity(repositoryClass="Teapot\ForumBundle\Repository\UserStatRepository")
 */
class UserStat extends BaseUserStat implements UserStatInterface
{

    /**
     * @var User $user
     *
     * @ORM\OneToOne(targetEntity="Teapot\UserBundle\Entity\User", inversedBy="forumStat")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

}
