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

use Teapot\Base\ForumBundle\Entity\BoardInterface;
use Teapot\Base\ForumBundle\Entity\TopicInterface;

use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\Common\Collections\ArrayCollection;

use Teapot\Base\ForumBundle\Service\PathService as BasePathService;

class PathService extends BasePathService
{

}