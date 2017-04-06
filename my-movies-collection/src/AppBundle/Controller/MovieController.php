<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/movie")
 */
class MovieController extends Controller
{
    /**
     * @Route("/grid")
     * @Template("AppBundle:Movie:showGrid.html.twig")
     * @return array
     */
    public function showGridAction()
    {
        $movies = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Movie')->findAll();

        return [
            'movies' => $movies
        ];
    }


}