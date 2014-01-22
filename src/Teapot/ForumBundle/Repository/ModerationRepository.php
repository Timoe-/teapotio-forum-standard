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

use Teapot\Base\ForumBundle\Repository\ModerationRepository as EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ModerationRepository extends EntityRepository
{
    /**
     * Get the latest moderation actions
     *
     * @param  integer  $offset
     * @param  integer  $limit
     *
     * @return Paginator
     */
    public function getLatestModerations($offset, $limit)
    {
        $queryBuilder = $this->createQueryBuilder('mo')
                             ->select(array('mo', 'u', 'f', 'm', 't', 'b'))
                             ->join('mo.user', 'u')
                             ->leftJoin('mo.flag', 'f')
                             ->leftJoin('mo.message', 'm')
                             ->leftJoin('mo.topic', 't')
                             ->leftJoin('mo.board', 'b')
                             ->orderBy('mo.id', 'DESC');

        $query = $queryBuilder->getQuery();

        $query->setFirstResult($offset)
              ->setMaxResults($limit);

        $paginator = new Paginator($query, false);
        return $paginator->setUseOutputWalkers(false);
    }

}