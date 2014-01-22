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
use Teapot\Base\ForumBundle\Entity\Message as BaseMessage;

use Teapot\Base\ForumBundle\Entity\MessageInterface;

/**
 * Teapot\ForumBundle\Entity\Message
 *
 * @ORM\Table(name="forum_message")
 * @ORM\Entity(repositoryClass="Teapot\ForumBundle\Repository\MessageRepository")
 */
class Message extends BaseMessage implements MessageInterface
{

    /**
     * @var User $user
     *
     * @ORM\ManyToOne(targetEntity="Teapot\UserBundle\Entity\User", fetch="EAGER")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var \Teapot\ForumBundle\Entity\Topic $topic
     *
     * @ORM\ManyToOne(targetEntity="Teapot\ForumBundle\Entity\Topic", inversedBy="messages", fetch="EAGER")
     * @ORM\JoinColumn(name="topic_id", referencedColumnName="id")
     */
    protected $topic;

    /**
     * @var ArrayCollection $stars
     *
     * @ORM\OneToMany(targetEntity="Teapot\ForumBundle\Entity\MessageStar", mappedBy="message")
     */
    protected $stars;

}