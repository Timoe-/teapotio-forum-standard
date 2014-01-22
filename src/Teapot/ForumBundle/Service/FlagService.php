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

use Teapot\ForumBundle\Entity\Flag;
use Teapot\Base\ForumBundle\Service\FlagService as BaseFlagService;

class FlagService extends BaseFlagService
{
    public function createFlag()
    {
        return new Flag();
    }

}