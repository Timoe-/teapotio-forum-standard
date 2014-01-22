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
use Teapot\Base\ForumBundle\Entity\Flag as BaseFlag;
use Doctrine\Common\Collections\ArrayCollection;

use Teapot\Base\ForumBundle\Entity\FlagInterface;

/**
 * Teapot\ForumBundle\Entity\Flag
 *
 * @ORM\Table(name="`forum_flag`")
 * @ORM\Entity(repositoryClass="Teapot\ForumBundle\Repository\FlagRepository")
 */
class Flag extends BaseFlag implements FlagInterface
{
    /**
     * @var ArrayCollection $users
     *
     * @ORM\ManyToMany(targetEntity="Teapot\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $users;

    /**
     * @var \Teapot\ForumBundle\Entity\Topic $topic
     *
     * @ORM\OneToOne(targetEntity="Teapot\ForumBundle\Entity\Topic", fetch="EAGER")
     * @ORM\JoinColumn(name="topic_id", referencedColumnName="id")
     */
    protected $topic;

    /**
     * @var \Teapot\ForumBundle\Entity\Message $message
     *
     * @ORM\OneToOne(targetEntity="Teapot\ForumBundle\Entity\Message", fetch="EAGER")
     * @ORM\JoinColumn(name="message_id", referencedColumnName="id")
     */
    protected $message;

    /**
     * @var \Teapot\ForumBundle\Entity\Moderation $moderation
     *
     * @ORM\OneToOne(targetEntity="Teapot\ForumBundle\Entity\Moderation", inversedBy="flag", fetch="EAGER")
     * @ORM\JoinColumn(name="moderation_id", referencedColumnName="id")
     */
    protected $moderation;
}