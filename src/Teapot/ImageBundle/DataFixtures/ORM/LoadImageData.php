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

namespace Teapot\Base\UserBundle\DataFixtures\ORM;

use Teapot\ImageBundle\Entity\Image;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadImageData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $image = new Image();

        $image->setName('default_image.png');

        $manager->persist($image);
        $manager->flush();

        $this->addReference('image-default-avatar', $image);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 50;
    }
}