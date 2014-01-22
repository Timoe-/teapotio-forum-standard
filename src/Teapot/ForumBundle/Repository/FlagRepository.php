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

use Teapot\ForumBundle\Entity\Moderation;

use Teapot\Base\ForumBundle\Repository\FlagRepository as EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class FlagRepository extends EntityRepository
{

}