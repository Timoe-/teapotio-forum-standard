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

use Teapot\ForumBundle\Entity\Moderation;
use Teapot\Base\ForumBundle\Service\ModerationService as BaseModerationService;

class ModerationService extends BaseModerationService
{
    public function createModeration()
    {
        return new Moderation();
    }

}