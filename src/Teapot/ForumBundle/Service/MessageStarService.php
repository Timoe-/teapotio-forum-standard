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

use Teapot\ForumBundle\Entity\MessageStar;
use Teapot\Base\ForumBundle\Service\MessageStarService as BaseMessageStarService;

class MessageStarService extends BaseMessageStarService
{
    public function createMessageStar()
    {
        return new MessageStar();
    }

}