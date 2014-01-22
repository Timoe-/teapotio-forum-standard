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

use Teapot\ForumBundle\Entity\UserStat;

use Teapot\Base\ForumBundle\Repository\UserStatRepository as EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Doctrine\Common\Collections\ArrayCollection;

class UserStatRepository extends EntityRepository
{

    /**
     * Get the top users
     *
     * @param   integer  $limit = 10
     *
     * @return  ArrayCollection
     */
    public function getTopUsers($limit = 10)
    {
        $queryBuilder = $this->createQueryBuilder('ufs')
                             ->select(array('ufs', 'u', 'us'))
                             ->join('ufs.user', 'u')
                             ->join('u.settings', 'us')
                             ->orderBy('ufs.totalMessage', 'desc')
                             ->addOrderBy('ufs.totalTopic', 'desc')
                             ->setMaxResults($limit);

        $query = $queryBuilder->getQuery();

        return new ArrayCollection($query->getResult());
    }

}