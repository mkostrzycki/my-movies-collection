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
     * @Template("AppBundle:Front/Movie:showGrid.html.twig")
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

    /**
     * @Route("/{movieId}/show")
     * @Template("AppBundle:Front/Movie:showMovieDetails.html.twig")
     * @param $movieId
     * @return array
     */
    public function showMovieDetails($movieId)
    {
        $movie = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Movie')->find($movieId);

        if (!$movie) {
            throw $this->createNotFoundException('Movie not found');
        }

        return [
            'movie' => $movie
        ];
    }
}
