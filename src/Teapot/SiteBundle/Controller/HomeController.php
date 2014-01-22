<?php

/**
 * Copyright (c) Thomas Potaire
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @category   Teapot
 * @package    SiteBundle
 * @author     Thomas Potaire
 */

namespace Teapot\SiteBundle\Controller;

use Teapot\Components\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
    	$em = $this->get('doctrine')->getManager();

    	$topics = $em->getRepository('TeapotForumBundle:Topic')->getLatestTopics(0, 40);

    	foreach ($topics as $topic) {
    		print_r(get_class($topic));
    	}

    	$params = array(
    		'topics' => $topics
    	);

        return $this->render('TeapotSiteBundle:Home:index.html.twig', $params);
    }
}
