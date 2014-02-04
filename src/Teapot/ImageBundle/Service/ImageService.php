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

namespace Teapot\ImageBundle\Service;

use Teapot\ImageBundle\Entity\Image;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ImageService {

    protected $container;
    protected $em;

    protected $imageRepositoryClass;

    public function __construct (ContainerInterface $container)
    {
        $this->container = $container;
        $this->em = $container->get('doctrine')->getManager();

        $this->imageRepositoryClass = $this->container->getParameter('teapot_image.image_repository.class');
    }

    /**
     * Create an image object
     *
     * @return Image
     */
    public function createImage()
    {
        return new Image();
    }

    /**
     * Save an Image object|entity
     *
     * @param  Image  $image
     *
     * @return Image
     */
    public function save(Image $image)
    {
        $this->em->persist($image);
        $this->em->flush();

        return $image;
    }

    /**
     * Setup the default image objects
     *
     * @return array
     */
    public function setup()
    {
        $image = $this->createImage();
        $image->setName('Default avatar');
        $image->setPath('default_avatar.png');

        $this->save($image);

        return array($image);
    }

}