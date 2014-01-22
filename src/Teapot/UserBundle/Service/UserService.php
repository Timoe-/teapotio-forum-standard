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

namespace Teapot\UserBundle\Service;

use Teapot\UserBundle\Entity\User;
use Teapot\UserBundle\Entity\UserToken;
use Teapot\UserBundle\Entity\UserSettings;

use Teapot\ForumBundle\Entity\UserStat;

use Teapot\Base\UserBundle\Service\UserService as BaseService;

class UserService extends BaseService {

    public function createUser()
    {
        return new User();
    }

    public function createUserToken()
    {
        return new UserToken();
    }

    /**
     * Save a user entity and make sure all required assocations are created
     *
     * @param  User   $user
     *
     * @return User
     */
    public function save(User $user)
    {
        if ($user->getSettings() === null) {
            $settings = new UserSettings();
            $settings->setUser($user);
            $user->setSettings($settings);

            $this->em->persist($settings);
        }

        if ($user->getForumStat() === null) {
            $forumStat = new UserStat();
            $forumStat->setUser($user);
            $user->setForumStat($forumStat);

            $this->em->persist($forumStat);
        }

        if ($user->getId() === null) {
            $user->setDateCreated(new \DateTime());
        }
        else {
            $user->setDateModified(new \DateTime());
        }

        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * Set the default avatar from its own avatars
     *
     * @param User    $user
     * @param integer $imageId
     *
     * @return User
     */
    public function setDefaultAvatarFromAvatars(User $user, $imageId)
    {
        foreach ($user->getAvatars() as $avatar) {
            if ($avatar->getId() == $imageId) {
                $user->setDefaultAvatar($avatar);

                $this->em->persist($user);
                $this->em->flush();
                break;
            }
        }

        return $user;
    }

    /**
     * Method only meant to run through command-line or during setup
     *
     * @param  string   $username
     * @param  string   $email
     * @param  string   $password
     * @param  array    $groups
     * @param  Image    $avatar
     *
     * @return array
     */
    public function setup($username, $email, $password, $groups, $images)
    {
        $user = $this->createUser();
        $user->setUsername($username);
        $user->setEmail($email);

        $user->setDateCreated(new \DateTime());
        $user->setSlug();

        $factory = $this->container->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);
        $password = $encoder->encodePassword($password, $user->getSalt());
        $user->setPassword($password);

        foreach ($groups as $group) {
            $user->addGroup($group);
        }

        $user->setDefaultAvatar($images[0]);

        $this->save($user);

        return array($user);
    }
}