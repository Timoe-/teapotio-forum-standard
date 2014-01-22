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

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Role\RoleInterface;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Teapot\UserBundle\Entity\UserSettings
 *
 * @ORM\Table(name="`user_settings`")
 * @ORM\Entity()
 */
class UserSettings
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var ArrayCollection $users
     *
     * @ORM\OneToOne(targetEntity="\Teapot\UserBundle\Entity\User", inversedBy="settings")
     */
    protected $user;

    /**
     * @var string
     *
     * @ORM\Column(name="background_color", type="string", length=6, nullable=true)
     */
    protected $backgroundColor;

    /**
     * @var string
     *
     * @ORM\Column(name="background_tiled", type="boolean", nullable=true)
     */
    protected $backgroundTiled = false;

    /**
     * @var \Teapot\ImageBundle\Entity\Image $backgroumdImage
     *
     * @ORM\ManyToOne(targetEntity="\Teapot\ImageBundle\Entity\Image")
     * @ORM\JoinColumn(name="background_image_id")
     */
    protected $backgroundImage;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param UserInterface $user
     * @return UserSettings
     */
    public function setUser(\Symfony\Component\Security\Core\User\UserInterface $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }


    /**
     * Set backgroundColor
     *
     * @param string $backgroundColor
     * @return UserSettings
     */
    public function setBackgroundColor($backgroundColor)
    {
        $this->backgroundColor = str_replace("#", "", $backgroundColor);

        return $this;
    }

    /**
     * Get backgroundColor
     *
     * @return string
     */
    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }

    /**
     * Set backgroundImage
     *
     * @param Teapot\ImageBundle\Entity\Image $backgroundImage
     * @return UserSettings
     */
    public function setBackgroundImage(\Teapot\ImageBundle\Entity\Image $backgroundImage = null)
    {
        $this->backgroundImage = $backgroundImage;

        return $this;
    }

    /**
     * Get backgroundImage
     *
     * @return Teapot\ImageBundle\Entity\Image
     */
    public function getBackgroundImage()
    {
        return $this->backgroundImage;
    }

    /**
     * Set backgroundTiled
     *
     * @param boolean $backgroundTiled
     * @return UserSettings
     */
    public function setBackgroundTiled($backgroundTiled)
    {
        $this->backgroundTiled = $backgroundTiled;

        return $this;
    }

    /**
     * Get backgroundTiled
     *
     * @return boolean
     */
    public function getBackgroundTiled()
    {
        return $this->backgroundTiled;
    }
}