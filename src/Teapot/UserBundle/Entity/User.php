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

use Teapot\Base\UserBundle\Entity\User as BaseUser;
use Teapot\UserBundle\Entity\UserSettings;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Teapot\UserBundle\Entity\User
 *
 * @ORM\Table(name="`user`", indexes={
 *     @ORM\Index(name="slug_idx", columns={"slug"})
 * })
 * @ORM\Entity(repositoryClass="Teapot\UserBundle\Repository\UserRepository")
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 */
class User extends BaseUser
{

    /**
     * @var ArrayCollection $groups
     *
     * @ORM\ManyToMany(targetEntity="\Teapot\UserBundle\Entity\UserGroup", inversedBy="users")
     */
    protected $groups;

    /**
     * @var ArrayCollection $avatars
     *
     * @ORM\ManyToMany(targetEntity="\Teapot\ImageBundle\Entity\Image")
     */
    protected $avatars;

    /**
     * @var \Teapot\ImageBundle\Entity\Image $defaultAvatar
     *
     * @ORM\ManyToOne(targetEntity="\Teapot\ImageBundle\Entity\Image")
     * @ORM\JoinColumn(name="default_avatar_id")
     */
    protected $defaultAvatar;

    /**
     * @var \Teapot\UserBundle\Entity\UserSettings $settings
     *
     * @ORM\OneToOne(targetEntity="\Teapot\UserBundle\Entity\UserSettings", mappedBy="user")
     */
    protected $settings;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", nullable=true)
     */
    protected $description;

    /**
     * @var User $user
     *
     * @ORM\OneToOne(targetEntity="Teapot\ForumBundle\Entity\UserStat", mappedBy="user")
     */
    protected $forumStat;

    /**
     * Holds all users permissions
     *
     * By default, null so we can test if it has been processed a certain time
     *
     * @var array|null
     */
    protected $permissions = null;

    public function __construct()
    {
        $this->avatars = new ArrayCollection();

        parent::__construct();
    }

    /**
     * Set default avatar
     *
     * @param Image $defaultAvatar
     * @return User
     */
    public function setDefaultAvatar(\Teapot\ImageBundle\Entity\Image $defaultAvatar)
    {
        $this->defaultAvatar = $defaultAvatar;
        return $this;
    }

    /**
     * Get default avatar
     *
     * @return Image
     */
    public function getDefaultAvatar()
    {
        return $this->defaultAvatar;
    }

    /**
     * Return a collection of avatar
     *
     * @return ArrayCollection
     */
    public function getAvatars()
    {
        return $this->avatars;
    }

    /**
     * Return a collection of avatar
     *
     * @param ArrayCollection $avatars
     * @return User
     */
    public function setAvatars(ArrayCollection $avatars)
    {
        $this->avatars = $avatars;
        return $this;
    }

    /**
     * Set avatar image
     *
     * @param \Teapot\ImageBundle\Entity\Image $avatar
     * @return User
     */
    public function addAvatar(\Teapot\ImageBundle\Entity\Image $avatar)
    {
        $this->avatars[] = $avatar;
        return $this;
    }

    /**
     * Set settings
     *
     * @param UserSettings $settings
     * @return User
     */
    public function setSettings(UserSettings $settings)
    {
        $this->settings = $settings;
        return $this;
    }

    /**
     * Get settings
     *
     * @return UserSettings
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return User
     */
    public function setDescription($description = null)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get forum stat
     *
     * @return \Teapot\ForumBundle\Entity\UserStat
     */
    public function getForumStat()
    {
        return $this->forumStat;
    }

    /**
     * Set forum stat
     *
     * @param \Teapot\ForumBundle\Entity\UserStat $userStat
     *
     * @return User
     */
    public function setForumStat(\Teapot\ForumBundle\Entity\UserStat $userStat)
    {
        $this->forumStat = $userStat;
        return $this;
    }

    /**
     * Get all permissions of a user
     *
     * @return array
     */
    public function getPermissions()
    {
        if ($this->permissions !== null) {
            return $this->permissions;
        }

        $this->permissions = array();

        foreach ($this->getGroups() as $group) {
            foreach ($group->getPermissions() as $action => $classes) {
                foreach ($classes as $className => $ids) {
                    if (isset($this->permissions[$action][$className]) === true) {
                        $this->permissions[$action][$className] = array_merge($this->permissions[$action][$className], $ids);
                    } else {
                        $this->permissions[$action][$className] = $ids;
                    }
                }
            }
        }

        return $this->permissions;
    }
}