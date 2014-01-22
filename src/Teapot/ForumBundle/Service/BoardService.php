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

use Teapot\ForumBundle\Entity\Board;
use Teapot\ForumBundle\Entity\BoardStat;

use Teapot\Base\ForumBundle\Entity\BoardInterface;

use Symfony\Component\Security\Core\User\UserInterface;

use Teapot\Base\ForumBundle\Service\BoardService as BaseBoardService;

class BoardService extends BaseBoardService
{
    public function createBoard()
    {
        return new Board();
    }

    /**
     * Build a single slug for a board
     * It will go through the parent slug and attach them together
     *
     * @param  BoardInterface  $board
     *
     * @return string
     */
    public function buildSlug(BoardInterface $board)
    {
        $useId = $this->container->getParameter('teapot.forum.url.use_id');

        $parents = $board->getParents();

        $boardSlugParts = array();
        if ($useId === false) {
            foreach ($parents as $parent) {
                $boardSlugParts[] = $parent->getSlug();
            }

            $boardSlugParts[] = $board->getSlug();
        }
        else {
            foreach ($parents as $parent) {
                $boardSlugParts[] = $parent->getSlug() .'-'. $parent->getId();
            }

            $boardSlugParts[] = $board->getSlug() .'-'. $board->getId();
        }

        return implode('/', $boardSlugParts);
    }

    /**
     * Create default boards
     *
     * @param  UserInterface $user
     *
     * @return array    array of boards
     */
    public function setup(UserInterface $user)
    {
        $board = $this->createBoard();

        $board->setTitle('General');
        $board->setShortTitle('General');
        $board->setSlug();
        $board->setUser($user);

        $this->save($board);

        return array($board);
    }
}