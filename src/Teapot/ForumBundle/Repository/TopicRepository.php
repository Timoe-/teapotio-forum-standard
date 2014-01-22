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

use Teapot\Base\ForumBundle\Entity\BoardInterface;

use Teapot\Base\ForumBundle\Repository\TopicRepository as EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Doctrine\Common\Collections\ArrayCollection;

class TopicRepository extends EntityRepository
{

    public function getLatestTopicsByBoard(BoardInterface $board, $offset, $limit)
    {
        $query = $this->createQueryBuilder('t')
                      ->select(array('t'))
                      ->where('t.board = :board')->setParameter('board', $board)
                      ->addOrderBy('t.isPinned', 'DESC')
                      ->addOrderBy('t.lastMessageDate', 'DESC')
                      ->getQuery()
                      ->setFirstResult($offset)
                      ->setMaxResults($limit);

        $paginator = new Paginator($query, false);
        return $paginator->setUseOutputWalkers(false);
    }

}