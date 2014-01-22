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

namespace Teapot\ForumBundle\Service;

use Teapot\ForumBundle\Entity\Topic;
use Teapot\ForumBundle\Entity\TopicStat;
use Teapot\Base\ForumBundle\Service\TopicService as BaseTopicService;
use Teapot\Base\ForumBundle\Entity\BoardInterface;

use Symfony\Component\Security\Core\User\UserInterface;

class TopicService extends BaseTopicService
{
    public function createTopic()
    {
        return new Topic();
    }

    /**
     * Create the first topic
     *
     * @param  UserInterface   $user
     * @param  BoardInterface  $board
     *
     * @return array
     */
    public function setup(UserInterface $user, BoardInterface $board)
    {
        $topic = $this->createTopic();

        $topic->setTitle('Welcome on your new Teapot forum!');
        $topic->setUser($user);
        $topic->setSlug();
        $topic->setBoard($board);

        $this->save($topic);

        $message = $this->container->get('teapot.forum.message')->createMessage();
        $message->setBody("<p>We would like to welcome you and we hope you will enjoy Teapot forum as much as we do.");
        $message->setIsTopicBody(true);
        $message->setPosition(1);
        $message->setUser($user);
        $message->setTopic($topic);

        $this->container->get('teapot.forum.message')->save($message);

        return array($topic);
    }
}