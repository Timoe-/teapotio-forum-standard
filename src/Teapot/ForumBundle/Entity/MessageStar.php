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
use Teapot\Base\ForumBundle\Entity\MessageStar as BaseMessageStar;

use Teapot\Base\ForumBundle\Entity\MessageStarInterface;

/**
 * Teapot\ForumBundle\Entity\MessageStar
 *
 * @ORM\Table(name="forum_message_star")
 * @ORM\Entity(repositoryClass="Teapot\ForumBundle\Repository\MessageStarRepository")
 */
class MessageStar extends BaseMessageStar implements MessageStarInterface
{

    /**
     * @var User $user
     *
     * @ORM\ManyToOne(targetEntity="Teapot\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var \Teapot\ForumBundle\Entity\Message $message
     *
     * @ORM\ManyToOne(targetEntity="Teapot\ForumBundle\Entity\Message", inversedBy="stars")
     * @ORM\JoinColumn(name="message_id", referencedColumnName="id")
     */
    protected $message;

}