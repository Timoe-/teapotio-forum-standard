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

use Doctrine\Common\Collections\ArrayCollection;
use Teapot\Base\ForumBundle\Entity\Board as BaseBoard;

use Teapot\Base\ForumBundle\Entity\BoardInterface;

/**
 * Teapot\ForumBundle\Entity\Board
 *
 * @ORM\Table(name="forum_board", indexes={
 *     @ORM\Index(name="slug_idx", columns={"slug"})
 * })
 * @ORM\Entity(repositoryClass="Teapot\ForumBundle\Repository\BoardRepository")
 */
class Board extends BaseBoard implements BoardInterface
{

    /**
     * @var User $user
     *
     * @ORM\ManyToOne(targetEntity="Teapot\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var ArrayCollection $topics
     *
     * @ORM\OneToMany(targetEntity="Teapot\ForumBundle\Entity\Topic", mappedBy="board")
     */
    protected $topics;

    /**
     * @var \Teapot\ForumBundle\Entity\Board $board
     *
     * @ORM\ManyToOne(targetEntity="Teapot\ForumBundle\Entity\Board", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent;

    /**
     * @var ArrayCollection $children
     *
     * @ORM\OneToMany(targetEntity="Teapot\ForumBundle\Entity\Board", mappedBy="parent")
     */
    protected $children;

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