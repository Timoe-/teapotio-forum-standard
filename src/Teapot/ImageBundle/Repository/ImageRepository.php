<?php

/**
 * Copyright (c) Thomas Potaire
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @category   Teapot
 * @package    ImageBundle
 * @author     Thomas Potaire
 */

namespace Teapot\ImageBundle\Repository;

use Teapot\ImageBundle\Entity\Image;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ImageRepository extends EntityRepository
{

}