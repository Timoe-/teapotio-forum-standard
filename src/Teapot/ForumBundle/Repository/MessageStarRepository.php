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

namespace Teapot\ForumBundle\Repository;

use Teapot\ForumBundle\Entity\Message;

use Teapot\Base\ForumBundle\Repository\MessageStarRepository as EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class MessageStarRepository extends EntityRepository
{

}