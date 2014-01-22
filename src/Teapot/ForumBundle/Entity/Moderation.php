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
use Teapot\Base\ForumBundle\Entity\Moderation as BaseModeration;

use Teapot\Base\ForumBundle\Entity\ModerationInterface;

/**
 * Teapot\ForumBundle\Entity\Moderation
 *
 * @ORM\Table(name="`forum_moderation`")
 * @ORM\Entity(repositoryClass="Teapot\ForumBundle\Repository\ModerationRepository")
 */
class Moderation extends BaseModeration implements ModerationInterface
{

    /**
     * @var User $user
     *
     * @ORM\ManyToOne(targetEntity="Teapot\UserBundle\Entity\User", fetch="EAGER")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var \Teapot\ForumBundle\Entity\Board $board
     *
     * @ORM\ManyToOne(targetEntity="Teapot\ForumBundle\Entity\Board")
     * @ORM\JoinColumn(name="board_id", referencedColumnName="id")
     */
    protected $board;

    /**
     * @var \Teapot\ForumBundle\Entity\Topic $topic
     *
     * @ORM\ManyToOne(targetEntity="Teapot\ForumBundle\Entity\Topic", fetch="EAGER")
     * @ORM\JoinColumn(name="topic_id", referencedColumnName="id")
     */
    protected $topic;

    /**
     * @var \Teapot\ForumBundle\Entity\Message $message
     *
     * @ORM\ManyToOne(targetEntity="Teapot\ForumBundle\Entity\Message", fetch="EAGER")
     * @ORM\JoinColumn(name="message_id", referencedColumnName="id")
     */
    protected $message;

    /**
     * @var \Teapot\ForumBundle\Entity\Flag $flag
     *
     * @ORM\OneToOne(targetEntity="Teapot\ForumBundle\Entity\Flag", mappedBy="moderation", fetch="EAGER")
     */
    protected $flag;
}