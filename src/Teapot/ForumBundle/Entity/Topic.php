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
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Teapot\Base\ForumBundle\Entity\Topic as BaseTopic;

use Teapot\Base\ForumBundle\Entity\TopicInterface;

/**
 * Teapot\ForumBundle\Entity\topic
 *
 * @ORM\Table(name="forum_topic", indexes={
 *     @ORM\Index(name="slug_idx", columns={"slug"}),
 *     @ORM\Index(name="last_message_date_idx", columns={"last_message_date"})
 * })
 * @ORM\Entity(repositoryClass="Teapot\ForumBundle\Repository\TopicRepository")
 */
class Topic extends BaseTopic implements TopicInterface
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
     * @ORM\ManyToOne(targetEntity="Teapot\ForumBundle\Entity\Board", inversedBy="topics")
     * @ORM\JoinColumn(name="board_id", referencedColumnName="id")
     */
    protected $board;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Teapot\ForumBundle\Entity\Message", mappedBy="topic")
     */
    protected $messages;

    /**
     * @var User $user
     *
     * @ORM\ManyToOne(targetEntity="Teapot\UserBundle\Entity\User", fetch="EAGER")
     * @ORM\JoinColumn(name="last_user_id", referencedColumnName="id")
     */
    protected $lastUser;

    /**
     * @var integer $legacyId
     *
     * @ORM\Column(name="legacy_id", type="integer", nullable=true)
     */
    protected $legacyId;

    /**
     * Set legacyId
     *
     * @param integer $legacyId
     * @return User
     */
    public function setLegacyId($legacyId)
    {
        $this->legacyId = $legacyId;

        return $this;
    }

    /**
     * Get legacyId
     *
     * @return integer
     */
    public function getLegacyId()
    {
        return $this->legacyId;
    }

}