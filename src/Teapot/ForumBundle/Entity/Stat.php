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
use Teapot\Base\ForumBundle\Entity\Stat as BaseStat;

use Teapot\Base\ForumBundle\Entity\StatInterface;

/**
 * Teapot\ForumBundle\Entity\Stat
 *
 * @ORM\Table(name="`forum_stat`")
 * @ORM\Entity
 */
class Stat extends BaseStat implements StatInterface
{

}