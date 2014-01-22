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

namespace Teapot\UserBundle\Entity;

use Teapot\Base\UserBundle\Entity\UserToken as BaseUserToken;

use Doctrine\ORM\Mapping as ORM;

/**
 * Teapot\UserBundle\Entity\UserToken
 *
 * @ORM\Table(name="`user_token`")
 * @ORM\Entity()
 */
class UserToken extends BaseUserToken
{

    /**
     * @var User $user
     *
     * @ORM\OneToOne(targetEntity="\Teapot\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

}